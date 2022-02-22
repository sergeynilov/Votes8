<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

use DB;
use Illuminate\Support\Facades\Storage;
use App\MyAppModel;
use App\Http\Traits\funcsTrait;

class Download extends MyAppModel
{
    use funcsTrait;

    protected $table      = 'downloads';
    protected $primaryKey = 'id';
    public $timestamps    = false;

    protected $fillable = [ 'title', 'file', 'description', 'creator_id', 'preview_image', 'price', 'price_info', 'created_at' ];
    protected static $uploads_downloads_dir = 'downloads/-download-';


    public function creator()
    {
        return $this->belongsTo('App\User', 'creator_id');
    }


    public function scopeGetByCreatorId($query, $creator_id = null)
    {
        if (empty($creator_id)) {
            return $query;
        }
        return $query->where(with(new Download)->getTable() . '.creator_id', $creator_id);
    }


    public function scopeGetByActive($query, $active = null)
    {
        if (!isset($active) or strlen($active) == 0) {
            return $query;
        }
        return $query->where(with(new Download)->getTable().'.active', $active);
    }


    public function scopeGetByTitle($query, $title = null, $partial = false)
    {
        if ( ! isset($title)) {
            return $query;
        }
        return $query->where(with(new Download)->getTable().'.title', (! $partial ? '=' : 'like'), ($partial ? '%' : '') . $title . ($partial ? '%' : ''));
    }


    public static function getDownloadDir(int $download_id): string
    {
        return self::$uploads_downloads_dir . $download_id . '/';
    }

    public static function getDownloadFilePath(int $download_id, $file): string
    {
        if (empty($file)) {
            return '';
        }
        return self::$uploads_downloads_dir . $download_id . '/' . $file;
    }


    public static function setDownloadFileProps(int $download_id, string $file = null, bool $skip_non_existing_file = false): array
    {
        if (empty($file) and $skip_non_existing_file) {
            return [];
        }

        $dir_path = self::$uploads_downloads_dir . '' . $download_id . '';
        $file_full_path = $dir_path . '/' . $file;
        $file_exists    = ( !empty($file) and Storage::disk('local')->exists('public/' . $file_full_path) );

        if ( ! $file_exists) {
            if ($skip_non_existing_file) {
                return [];
            }
            $file_full_path = /*'public' . */
                config('app.empty_img_url');
            $file          = with(new Download)->getFilenameBasename($file_full_path);
        }

        $file_path = $file_full_path;
        if ($file_exists) {
            $file_url       = 'storage/app/' . $dir_path . '/' . $file;
            $filePropsArray = ['file' => $file, 'file_path' => $file_path, 'file_url' => '/storage/' . $dir_path . '/' . $file];
            $file_full_path = base_path() . '/storage/app/public/' . $file_path;
        } else {
            $filePropsArray = ['file' => $file, 'file_path' => $file_path, 'file_url' => $file_full_path];
            $file_full_path = base_path() . '/public/' . $file_path;
        }

        if ( ! empty($previewSizeArray['width'])) {
            $filePropsArray['preview_width']  = $previewSizeArray['width'];
            $filePropsArray['preview_height'] = $previewSizeArray['height'];
        }
        $downloadImgProps = with(new Download)->getCFFileProps($file_full_path, $filePropsArray);

        return $downloadImgProps;

    }

    public static function getValidationRulesArray($download_id = null, array $options= []): array
    {
        $validationRulesArray = [
/*            'title' => [
                'required',
                'string',
                'max:255',
                Rule::unique(with(new Download)->getTable())->ignore($download_id),
            ],

            'description'  => 'required',
//            'mime'       => 'required|in:' . with(new Download)->getValueLabelKeys(Download::getDownloadStatusValueArray(false)),
            'creator_id'   => 'required|integer|exists:' . (with(new User)->getTable()) . ',id',*/
        ];


        return $validationRulesArray;
    }


    public static function getDownloadsSelectionArray(string $filter_title=null) :array {
        $downloads = Download::orderBy('title','desc')->getByTitle($filter_title)->get();
        $downloadsSelectionArray= [];
        foreach( $downloads as $nextDownload ) {
            $downloadsSelectionArray[$nextDownload->id]= $nextDownload->title;
        }
        return $downloadsSelectionArray;
    }

}