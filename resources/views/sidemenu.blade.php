<div id="main-navigation" class="menu-wrapper" style="left: 0px; width: 160px; transition-property: all; -webkit-transition-property: all; transition-duration: 0s; -webkit-transition-duration: 0s; transition-timing-function: ease; -webkit-transition-timing-function: ease;">
    <div class="main-menu">
        
        <div class="logo--main">
            <div class="menu-icon" id="sidebarclose" style="left: 160px; transition-property: all; -webkit-transition-property: all; transition-duration: 0s; -webkit-transition-duration: 0s; transition-timing-function: ease; -webkit-transition-timing-function: ease;">
                <!-- <a href="#main-navigation" class="nav-slide">HELLO<span class="glyphicon glyphicon-remove"></span></a> -->
                <!-- <span class="glyphicon glyphicon-remove"></span> -->
                <span class="fa fa-times fa-4x"></span>
            </div>
            @if (Request::path() != 'home' && (isset($famid)) && isset($faminfo))
                <div>
                    <a href={{ url("family/$famid")}} <span id="sidefamname" title="{{ "$faminfo[$famid]" }}"> {{ substr($faminfo[$famid],0,18) }} </span></a>
                </div>
            @endif
            
        </div>
       
        @if (Request::path() == 'home' || (!isset($famid)))
            <div class="nav">
                <ul class="mainmenuDrop">
                    <li class="nav--drop">
                            <!--a title="Update Family">Family Edit <span id="fam_edit_menu_expand" class="glyphicon glyphicon-plus"> <span id="fam_edit_menu_collapse" class="glyphicon glyphicon-minus" style="display: none;"> </span></a-->
                            <a title="Update Family">Edit Family  <span class="glyphicon glyphicon-plus">   </span></a>
                            <!--ul data-index="1" style="display: none;"-->
                            <ul data-index="1" class="display-none">
                                <li class="menu--sub"><a href={{ url("family/rename")}}>Rename</a></li>
                                <li class="menu--sub"><a href={{ url("family/delete")}}>Delete</a></li>
                            </ul>
                    </li>
                    <li><a href={{ url("family/join")}} title="Join Family">Join Families</a></li>
                    <li><a href={{ url("family/split")}} title="Split Family">Split Family</a></li>
                    <li><a href={{ url("family/copy")}} title="Copy Family">Copy Family</a></li>
                </ul>
            </div>
        @endif
        
        @if (Request::path() != 'home' && (isset($famid)))
            <div class="nav">
                <ul class="mainmenuDrop">
                    {{--@if(isset($viewonly) && (! $viewonly)) --}}
                    @if(! $userpref["access"][$famid]["viewonly"])
                    <li class="nav--drop">
                            <a title="Update Family Members">Members <span class="glyphicon glyphicon-plus"> </span></a>
                            <ul data-index="1" class="display-none">
                                <li class="menu--sub"><a href={{ url("family/$famid/person/add")}}>Add</a></li>
                                <li class="menu--sub"><a href={{ url("family/$famid/person/edit")}}>Edit</a></li>
                                <li class="menu--sub"><a href={{ url("family/$famid/person/delete")}}>Delete</a></li>   
                            </ul>
                    </li>
                     <li class="nav--drop">
                            <a title="Add/force extra relations">Relations <span class="glyphicon glyphicon-plus"> </span></a>
                            <ul data-index="1" class="display-none">
                                <li class="menu--sub"><a href={{ url("family/$famid/relation/add")}}>Add</a></li>
                                <li class="menu--sub"><a href={{ url("family/$famid/relation/delete")}}>Delete</a></li>   
                            </ul>
                    </li>
                    @endif
                   
                    <li class="nav--drop">
                            <a title="Find Amazing Things in Family">Find <span class="glyphicon glyphicon-plus"></span></a>
                            <ul data-index="1" class="display-none">
                                <li class="menu--sub" id="memberfind"><a href="">Person</a></li>
                                <li class="menu--sub" id="relationfind"><a href="">Relation Between</a></li>
                                <!-- <li class="menu--sub"><a href="">City | State | Country</a></li>                                 -->
                            </ul>
                    </li>

                    <!-- <li class="nav--drop">
                            <a title="">Generations <span class="glyphicon glyphicon-plus"></span></a>
                            <ul data-index="1" class="display-none">
                                <li class="menu--sub"><a href="">Same Generation</a></li>
                                <li class="menu--sub"><a href="">Prior Generations</a></li>
                                <li class="menu--sub"><a href="">Next Generations</a></li>
                            </ul>
                    </li> -->

                    <li class="nav--drop">
                            <a title="">Show Family<span class="glyphicon glyphicon-plus"></span></a>
                            <ul data-index="1" class="display-none">
                                <li class="menu--sub"><a href={{ url("family/$famid/list")}}>In a List</a></li>
                                <li class="menu--sub"><a href={{ url("family/$famid/map")}}>On the Map</a></li>
                            </ul>
                    </li>
    
                    {{-- @if(isset($viewonly) && (! $viewonly)) --}}
                    @if(! $userpref["access"][$famid]["viewonly"])
                    <li class="nav--drop">
                            <a title="">Share<span class="glyphicon glyphicon-plus"></span></a>
                            <ul data-index="1" class="display-none">
                                <li class="menu--sub"><a href={{ url("family/$famid/share")}}>Share Family</a></li>
                                <li class="menu--sub"><a href={{ url("family/$famid/access")}}>Change Access Right</a></li>
                            </ul>
                    </li>
                    @endif

                    <li><a href={{ url("family/$famid/setroot")}} title="Set Root">Set Root</a></li>
                </ul>
            </div>
            <script>var family_id = {{$famid}} ;</script>
        @endif    

    </div>

    <div class="sidebarmin">
        <a href="">
            <span class="glyphicon glyphicon-th-list"></span>
        </a>
    </div>    
</div>






