@extends('master')

@section('content')
	<div id="cy" ></div>

<script>
  var GLOBAL_elements = { {!! $cyobject !!}  };
  var GLOBAL_root = '#' + {!! $root !!} ;
  var GLOBAL_p1 = '{!! $global_vars['p1'] !!}' ;
  var GLOBAL_p2 = '{!! $global_vars['p2'] !!}' ;
  var GLOBAL_edge = '{!! $global_vars['edge'] !!}' ;
  var GLOBAL_rel = '{!! $global_vars['relation'] !!}' ;

</script>

@endsection