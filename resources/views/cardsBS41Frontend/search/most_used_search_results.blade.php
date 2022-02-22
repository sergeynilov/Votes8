    @inject('viewFuncs', 'App\library\viewFuncs')
    @if(count($mostUsedSearchResults) > 0)

        @if(!empty($is_developer_comp))
        {{--<div class="row ml-1">--}}
            {{--{!! $viewFuncs->showStylingCheckbox('cbx_search_in_votes', 1, false, '', []  ) !!}--}}


            {{--{!! $viewFuncs->showStylingCheckbox('cbx_search_in_pages', 1, false, '', []  ) !!}--}}


            {{--{!! $viewFuncs->showStylingCheckbox('cbx_search_in_news', 1, false, '', []  ) !!}--}}



        {{--</div>--}}
        @endif

        <div class="row ml-1 mt-2">
            Select category(ies)&nbsp;:<br>

{{--            {{ Form::select('voteCategories', $voteCategoriesSelectionArray, '', [ "id"=>"voteCategories", "class"=>"form-control " . "--}}
{{--editable_field ", 'multiple'=>'multiple' ] ) }}--}}
            {!! $viewFuncs->select('voteCategories', $voteCategoriesSelectionArray, '', "form-control editable_field chosen_select_box",
['data-placeholder'=>" -Select vote Category - "] ) !!}
            <input type="text" id="voteCategories" name="voteCategories" value="" style="visibility: hidden; width: 1px; height: 1px">

        </div>

        <div class="row ml-1">
            <h5>
                Select one of most used search results :
            </h5>

            <div class="table-responsive">
                <table class="table table-bordered text-primary">
                    @foreach($mostUsedSearchResults as $key=>$nextMostUsedSearchResult)
                        <tr>
                            <td>
                                <a href="#" class="a_link" onclick="javascript:selectSearchText('{{ $nextMostUsedSearchResult['text'] }}'); return false;">
                                    <span class="badge">{{ Purifier::clean($nextMostUsedSearchResult['text']) }}</span>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>

        </div>
    @endif