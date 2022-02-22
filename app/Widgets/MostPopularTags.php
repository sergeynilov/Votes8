<?php

namespace App\Widgets;

use DB;
use Arrilot\Widgets\AbstractWidget;
use App\MyTag;
use App\Settings;
use App\Taggable as MyTaggable;
use App\Http\Traits\funcsTrait;
use App\library\CheckValueType;

class MostPopularTags extends AbstractWidget
{
    use funcsTrait;
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [
        'items_per_block'         => 5,
        'order_by_field_name'     => 'taggables_count',
        'order_by_field_ordering' => 'desc',
        'show_no_items_label'     => false,
    ];

    public function __construct(array $config = [])
    {
        $this->config['items_per_block']= Settings::getValue('most_votes_taggable_on_homepage', CheckValueType::cvtInteger, 6);
        $this->config['items_per_block']= isset( $config['items_per_block'] ) ? $config['items_per_block'] : $this->config['items_per_block'];
        parent::__construct($config);
    }

    public function run()
    {
        $tags_tb= with(new MyTag)->getTable();
        $tagables_tb= with(new MyTaggable)->getTable();
        $mostVotesTaggableData     = [];
        $tempMostVotesTaggableData = MyTaggable
            ::select( $tagables_tb.'.tag_id as tag_id' , \DB::raw('count('.DB::getTablePrefix().$tagables_tb.'.taggable_id) as taggables_count') )
            ->orderBy($this->config['order_by_field_name'], $this->config['order_by_field_ordering'])
            ->groupBy($tagables_tb.'.tag_id')
            ->join($tags_tb, $tags_tb.'.id', '=', $tagables_tb.'.taggable_id')
            ->limit($this->config['items_per_block'])
            ->get();

        foreach ($tempMostVotesTaggableData as $next_key => $nextTempMostVotesTaggable) {
            $nextTag = MyTag::find($nextTempMostVotesTaggable->tag_id);
            if (empty($nextTag)) {
                continue;
            }
            $mostVotesTaggableData[] = [
                'tag_id'          => $nextTempMostVotesTaggable->tag_id,
                'taggables_count' => $nextTempMostVotesTaggable->taggables_count,
                'tag_name'        => $this->getSpatieTagLocaledValue($nextTag->name),
                'tag_slug'        => $this->getSpatieTagLocaledValue($nextTag->slug)
            ];
        }
        
        return view('widgets.most_popular_tags', [
            'config' => $this->config,
            'mostVotesTaggableData' => $mostVotesTaggableData,
        ]);
    }
}