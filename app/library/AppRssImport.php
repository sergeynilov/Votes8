<?php namespace App\library {

    use Auth;
    use DB;

    use Illuminate\Support\Facades\File;
    use Illuminate\Support\Facades\Storage;
    use Session;
    use Carbon\Carbon;
    use ImageOptimizer;

    use App\User;
    use App\ActivityLog;
    use App\PageContent;
//    use App\PageContentImage;
    use App\ExternalNewsImporting;
    use App\Http\Traits\funcsTrait;
    use App\Settings;
    use App\library\CheckValueType;


    class AppRssImport
    {
        use funcsTrait;


        public function workRssItems(bool $write_aActivity_log = false): int
        { //http://edition.cnn.com/services/rss/
            $items_added    = 0;


            $rssSourcesList = ExternalNewsImporting
                ::getByStatus(true)
                ->orderBy('id', 'asc')
                ->get();

            $request                = request();
            $feed_import_creator_id = Settings::getValue('feed_import_creator_id', CheckValueType::cvtInteger, 20);
//            echo '<pre>++ $feed_import_creator_id::' . print_r($feed_import_creator_id, true) . '</pre>';
            $feedImportCreator = User::find($feed_import_creator_id);
            if ($feedImportCreator === null) {
//                echo '<pre>$::'.print_r(000000,true).'</pre>';
                $feedImportCreator = User
                    ::getByStatus('A')
                    ->orderBy('id', 'asc')
                    ->first();
                if ($feedImportCreator === null) {
                    if ($write_aActivity_log) {
                        $newActivityLog              = new ActivityLog();
                        $newActivityLog->description = 'Default creator user not found from ip ' . ($request->ip());
                        $newActivityLog->causer_type = ActivityLog::CAUSER_TYPE_NO_FEED_DEFAULT_USER;
                        $newActivityLog->properties  = '';
                        $newActivityLog->save();
                    }

                    return 0;
                }
                $feed_import_creator_id = $feedImportCreator->id;
            }

            foreach ($rssSourcesList as $nextRssSource) { // all Rss Sources listing in site configurations

                $feed  = \Feeds::make($nextRssSource->url);
                $items = $feed->get_items();
                foreach ($items as $RssItem) { // add any nonempty row from next rss source
                    $item_description   = $RssItem->get_description();
                    $item_title         = $RssItem->get_title();
                    $item_pub_date      = $this->getPubDateAsTimestamp($RssItem);
                    $item_get_permalink = $RssItem->get_permalink();
                    if (empty($item_description) or empty($item_title) or empty($item_get_permalink)) {
                        continue;
                    }

                    $similar_page_content_count= PageContent::getSimilarPageContentBySourceUrl( $item_get_permalink, null, true );

                    if ($similar_page_content_count > 0) {
                        continue; // this item was imported prior so skip it
                    }

                    $newPageContent                  = new PageContent();

                    $newPageContent->title           = $item_title;
                    $newPageContent->content         = $item_description;

                    $newPageContent->content_shortly = $this->concatStr($item_description,250);
                    $newPageContent->creator_id      = $feed_import_creator_id;
                    $newPageContent->is_featured     = false;
                    $newPageContent->published       = true;
                    $newPageContent->is_homepage     = true;
                    $newPageContent->page_type       = 'E';
                    $newPageContent->source_type     = $nextRssSource['title'];
                    $newPageContent->source_url      = $item_get_permalink;

                    if (!empty($item_pub_date)) {
                        $newPageContent->created_at  = Carbon::createFromTimestamp($item_pub_date);
                    } else {
                        $newPageContent->created_at  = now();
                    }

                    $newPageContent->save();
                    $items_added++;

                    $enclosureSubitems               = $RssItem->get_enclosures();
                    $new_page_content_image_link     = '';
                    foreach ($enclosureSubitems as $nextEnclosureSubitem) {
                        if ( ! empty($nextEnclosureSubitem->link and $nextRssSource->import_image )) {
                            $new_page_content_image_valid_name = PageContent::checkValidImgName($nextEnclosureSubitem->link, with(new PageContent)->getImgFilenameMaxLength(), true);
                            $new_page_content_image_link = $nextEnclosureSubitem->link;
                            break;
                        }
                    }

                    if ( !empty($new_page_content_image_link) and $nextRssSource->import_image ) {
                        $filename_basename = $this->getFilenameBasename($new_page_content_image_valid_name);
//                        echo '<pre>$filename_basename::'.print_r($filename_basename,true).'</pre>';

                        $filename_basename_extension = $this->getFilenameExtension($new_page_content_image_valid_name);
//                        echo '<pre>$filename_basename_extension::'.print_r($filename_basename_extension,true).'</pre>';

                        $dest_image = 'public/' . PageContent::getPageContentImagePath($newPageContent->id, $filename_basename . '.' . $filename_basename_extension);
                        $new_page_content_contents = file_get_contents($new_page_content_image_link);

                        Storage::disk('local')->put($dest_image, $new_page_content_contents);
                        ImageOptimizer::optimize( storage_path().'/app/'.$dest_image, null );
                        $newPageContent->image = $filename_basename . '.' . $filename_basename_extension;
                        $newPageContent->save();
                    } // if ( !empty($page_content_image) ) {

                } // foreach ($items as $RssItem) { // add any nonempty row from next rss source

            } // foreach ($rssSourcesList as $nextRssSource) { // all $nextRssSource listing in site configurations

//            echo '<pre>LAST $items_added::' . print_r($items_added, true) . '</pre>';
            if ($items_added > 0) {
                if ($write_aActivity_log) {
                    $newActivityLog              = new ActivityLog();
                    $newActivityLog->description = 'Successful importing of ' . $items_added . ' rss news from ip ' . ($request->ip());
                    $newActivityLog->causer_type = ActivityLog::CAUSER_TYPE_SUCCESS_FEED_IMPORTING;
                    $newActivityLog->properties  = $items_added;
                    $newActivityLog->save();
                }
            }

            return $items_added;
        } // public function archiveUserRegistrationFiles()

        private function getPubDateAsTimestamp($RssItem) {
            $pub_date_value= '';
//            echo '<pre>$RssItem->data[\'child\'][""]["pubDate"]::'.print_r($RssItem->data['child'][""]["pubDate"],true).'</pre>';
            if ( !empty($RssItem->data['child'][""]["pubDate"]) and is_array($RssItem->data['child'][""]["pubDate"]) ) {
                $pubDateArray= $RssItem->data['child'][""]["pubDate"];
//                echo '<pre>$pubDateArray::'.print_r($pubDateArray,true).'</pre>';
                if ( !empty($pubDateArray[0]["data"]) ) {
                    $pubDate= $pubDateArray[0]["data"];
//                    echo '<pre>$pubDate::'.print_r($pubDate,true).'</pre>';
                    $pub_date_value= strtotime($pubDate);
//                    echo '<pre>$pub_date_value::'.print_r($pub_date_value,true).'</pre>';
                }
            }
            return $pub_date_value;
        }
        

    }


}