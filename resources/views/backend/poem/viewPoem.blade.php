@include('backend.common.header_main')
            <div class="content-page">
                <div class="content">
                    <div class="container-fluid">
                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box">
                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}">Dashbord</a></li>
                                            <li class="breadcrumb-item"><a href="{{url('admin/poem/list')}}">Item</a></li>
                                            <li class="breadcrumb-item active">View Item</li>
                                        </ol>
                                    </div>
                                    <h4 class="page-title">
                                        View Item
                                    </h4>
                                </div>
                            </div>
                        </div>     
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-lg-12 text-right">
                                                <a href="{{url('admin/poem/list')}}" class="btn btn-primary  mb-3">Back To Item</a>
                                            </div>
                                        </div>
                                        <table class="table">
                                          <tbody>
                                            <tr>
                                              <th scope="row">Title</th>
                                              <td>{{ @$itemData['ititle']}}</td>
                                            </tr>
                                            <tr>
                                              <th scope="row">Poet</th>
                                              <td>
                                                @foreach($creators as $creator)
                                                @if($creator['creatorid']==$itemData['creatorid'])
                                                    {{@$creator->cname}}
                                                @endif
                                                @endforeach
                                              </td>
                                            </tr>
                                            <tr>
                                              <th scope="row">Year</th>
                                              <td>
                                                {{$itemData['iyear']}}
                                              </td>
                                            </tr>
                                            <tr>
                                              <th scope="row">Item Description</th>
                                              <td >
                                                {!! @$itemData['itemDetail']['itext'] !!}
                                              </td>
                                            </tr>
                                            <tr>
                                              <th scope="row">Source Text</th>
                                              <td>
                                                {{$itemData['ctext']}}
                                              </td>
                                            </tr>
                                              <tr>
                                              <th scope="row">Source Url</th>
                                              <td>
                                                {{$itemData['icontent_url']}}
                                              </td>
                                            </tr>
                                            <tr>
                                              <th scope="row">Theme</th>
                                              <td>
                                                
                                                @foreach($themeList as $theme)
                                                @if (in_array($theme['item_text'], $selected_array)) 
                                                    @php
                                                        $theme_arr[] = @$theme['item_text'];
                                                    @endphp
                                                @endif
                                                @endforeach
                                                @if(!empty($theme_arr))
                                                {{implode(', ',@$theme_arr)}}
                                                @endif
                                              </td>
                                            </tr>
                                            <tr>
                                              <th scope="row">Mood</th>
                                              <td>
                                                
                                                @foreach($moodList as $mood)
                                                @if (in_array($mood['item_text'], $selected_mood_array)) 
                                                    @php 
                                                        $mood_txt[] = @$mood['item_text'];
                                                    @endphp
                                                 @endif
                                                 @endforeach
                                                  @if(!empty($mood_txt))
                                                 {{implode(', ',@$mood_txt)}}
                                                    @endif
                                              </td>
                                            </tr>
                                            @if($itemData['item_text1'] != "")
                                            <tr>
                                              <th scope="row">Additional Text 1 : {{$itemData['item_text1']}}</th>
                                              <td>
                                                Additional Url 1 : {{$itemData['iadd_url_1']}}
                                              </td>
                                            </tr>
                                            @endif
                                            @if($itemData['item_text2'] != "")
                                            <tr>
                                              <th scope="row">Additional Text 2 : {{$itemData['item_text2']}}</th>
                                              <td>
                                                Additional Url 2 : {{$itemData['iadd_url_2']}}
                                              </td>
                                            </tr>
                                            @endif
                                            @if($itemData['item_text3'] != "")
                                            <tr>
                                              <th scope="row">Additional Text 3 : {{$itemData['item_text3']}}</th>
                                              <td>
                                                Additional Url 3 : {{$itemData['iadd_url_3']}}
                                              </td>
                                            </tr>
                                            @endif
                                            <tr>
                                              <th scope="row">Notify me</th>
                                              <td>
                                                {{($itemData['notify_by'] == 1) ? 'By email' : ($itemData['notify_by'] == 2) ? 'On my mobile phone' : ($itemData['notify_by'] == 3) ? 'Both email and mobile phone' : 'No, thanks'}}
                                              </td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Status</th>
                                            <td>
                                                {{($itemData['approved_by_admin'] == 1) ? 'Active' : 'Inactive'}}
                                            </td>
                                            </tr>
                                          </tbody>
                                        </table>
                                    </div>
                                </div> 
                            </div>
                        </div> 
                    </div>
               @include('backend.common.footer')
           </div>
       </div>
       <div class="rightbar-overlay"></div>


