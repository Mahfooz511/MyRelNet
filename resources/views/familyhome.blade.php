@extends('master-new')

@section('headerfiles')
  <script src="{{ asset('/js/cytoscape/build/cytoscape.min.js') }}"></script>
  <script src="{{ asset('/js/relmap.js') }}"></script>

@endsection


@section('content')

<div class="container-fluid no-sidepadding " style="height:100%;">
	<div class="row " id="cyid" style="height:100%;"> 
    <div class="col-md-12" style="height:100%;"> 
      @if (Session::has('message'))
       <div class="alert alert-info alert-autoclose">{{ Session::get('message') }}</div>
      @endif
 		   <div  id="cy" style="height:100%;">
       
            <div id="toolbar" class="draggable  ui-widget-content" >
              <ul>
                <li><a href="" title="Show All Arrows." id="showall">ShowAll</a></li>
                <li><a href="" title="Hide All Arrows." id="hideall">HideAll</a></li>
                <li><a href="" title="Show Minimum Connecting Arrows." id="showmin">ShowMin</a></li>
                <li id="graphlayout"><a href="" title="Change Graph Layout.">Layout<i class="fa fa-caret-down"></i></a>
                    <ul>
                       <li><a href=""  id="layout7" title="Old Generation on Top">Tree</a></li>
                       <li><a href=""  id="layout1" title="Show Members in a Grid">Grid</a></li>
                       <li><a href=""  id="layout2" title="Show Members in a Circle">Circle</a></li>
                       <!-- <li><a href=""  id="layout3">Spread</a></li>
                       <li><a href=""  id="layout4">Springy</a></li> -->
                       <li><a href=""  id="layout5" title="Show Members in a Randon Order">Random</a></li>
                       <!-- <li><a href=""  id="layout6">BFS</a></li> -->

                    </ul>
                </li>
                <li><span><i class="fa fa-arrows-alt last" title="Hold and Move Toolbar"></i></span></li>
              </ul>
            </div>

       </div>
		</div>  
	</div>
</div>

<script>
  var GLOBAL_elements = { {!! $cyobject !!}  };
  var GLOBAL_root = '#' + {!! $root !!} ;
  {{-- var GLOBAL_p1 = '{!! $global_vars['p1'] !!}' ;
  var GLOBAL_p2 = '{!! $global_vars['p2'] !!}' ;
  var GLOBAL_edge = '{!! $global_vars['edge'] !!}' ;
  var GLOBAL_rel = '{!! $global_vars['relation'] !!}' ;
--}} 
  <?php  
    if ($person != null){
      echo "var findmem = $person ; " ;    
    }
    if($rfid1 != null && $rfid2 != null ){
      echo "var rfid1 = $rfid1 ; " ; 
      echo "var rfid2 = $rfid2 ; " ; 
    }
  ?>
  
  // Flash message auto close
  $("div.alert-autoclose").delay(3000).slideUp(300);

</script>
@endsection
