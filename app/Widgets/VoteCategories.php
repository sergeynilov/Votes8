<?php

namespace App\Widgets;

use Arrilot\Widgets\AbstractWidget;

use App\Vote;
use App\VoteCategory;
use App\Http\Traits\funcsTrait;

class VoteCategories extends AbstractWidget
{
    use funcsTrait;
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [
        'order_by_field_name'     => 'created_at',
        'order_by_field_ordering' => 'desc',
/*        'show_no_items_label'     => false,
        'filter_published'        => null,
        'filter_is_featured'      => null,
        'filter_is_homepage'      => null,*/

    ];


    public function __construct(array $config = [])
    {
/*        $this->config['ref_items_per_pagination']=  isset( $config['ref_items_per_pagination'] ) ? $config['ref_items_per_pagination'] : $this->config['ref_items_per_pagination'];
        $this->config['filter_published']= isset( $config['filter_published'] ) ? $config['filter_published'] : $this->config['filter_published'];
        $this->config['filter_is_featured']= isset( $config['filter_is_featured'] ) ? $config['filter_is_featured'] : $this->config['filter_is_featured'];
        $this->config['filter_is_homepage']= isset( $config['filter_is_homepage'] ) ? $config['filter_is_homepage'] : $this->config['filter_is_homepage'];*/
        parent::__construct($config);
    }

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        $activeVoteCategories= [];
        $tempActiveVoteCategories = VoteCategory
            ::getByActive(true)
            ->get();
        foreach( $tempActiveVoteCategories as $nextTempActiveVoteCategory ) {
            $votes_count = Vote
                ::getByVoteCategory($nextTempActiveVoteCategory->id)
                ->getByStatus('A')
                ->count();
            if( $votes_count > 0 ) {
                $activeVoteCategories[] = ['id' => $nextTempActiveVoteCategory->id, 'name' => $nextTempActiveVoteCategory->name, 'slug' => $nextTempActiveVoteCategory->slug, 'votes_count' => $votes_count];
            }
        }

//        echo '<pre>$this->config[\'order_by_field_name\']::'.print_r($this->config['order_by_field_name'],true).'</pre>';
//        echo '<pre>$this->config[\'order_by_field_ordering\']::'.print_r($this->config['order_by_field_ordering'],true).'</pre>';
//
//        echo '<pre> == $activeVoteCategories::'.print_r($activeVoteCategories,true).'</pre>';

        uasort($activeVoteCategories, array($this, 'cmpVoteCategories'));

//        echo '<pre>$activeVoteCategories::'.print_r($activeVoteCategories,true).'</pre>';
//        die("-1 XXZ");

        return view('widgets.vote_categories', [
            'config'                => $this->config,
            'activeVoteCategories'  => $activeVoteCategories
        ]);
    }

    //        {{ Widget::run('VoteCategories', ['order_by_field_name'=> 'votes_count', 'order_by_field_ordering'=>'desc']) }}

    public function cmpVoteCategories($a, $b) {

        if ( strtolower($this->config['order_by_field_ordering']) == 'desc' ) $index= -1;
        if ( strtolower($this->config['order_by_field_ordering']) == 'asc' ) $index= 1;

        if ( $this->config['order_by_field_name'] == 'votes_count' ) {
            if ( $a['votes_count'] == $b['votes_count'] ) {
                return 0;
            }
            return ( $a['votes_count'] < $b['votes_count'] ) ? $index * -1 : $index * 1;
        }

        if ( $this->config['order_by_field_name'] == 'name' ) {
            if ( strtolower($a['name']) == strtolower($b['name']) ) {
                return 0;
            }
            return ( strtolower($a['name']) < strtolower($b['name']) ) ? $index * -1 : $index * 1;
        }


        return 0;
    }

}
