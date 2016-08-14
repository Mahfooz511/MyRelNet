<?php namespace RelNet; 

use App\Person ;
use App\Family ;
use App\Relation ;
//use Graphp\Algorithms;
use Graphp\Algorithms\ShortestPath\Dijkstra;
use Fhaculty\Graph\Graph;
use App\Userpreferrence ;
use RelNet\RelationMetaData;



 class FamilyGraph  {

	//private $id ;
	private $name ;
	private $graph ;
	private $persons = [];
	private $relations;
	private $family_id ;
	private $rel_static_data ; 
	private $member_count ;

	public $lang = "Hindi" ;
	public $cy_object ;
	public $root ; 

	public function __construct($family_id){
		$this->rel_static_data = new RelationMetaData() ;
		$this->family_id = $family_id ;
		
		$this->load_data();
		
		$this->graph = new Graph();
		$this->create_family();

		$this->GetCYObject() ;

		//var_dump($this->graph);
	}

	private function load_data(){
		$rows = Person::where('family_id','=',$this->family_id)->get() ;
		$this->member_count = Person::where('family_id','=',$this->family_id)->count() ;
		$column_vals = [];
		foreach($rows as $row) {
		  $this->persons[$row->id]=$row;
		}

		$first_created_at = Person::where('family_id','=',$this->family_id)->min('created_at') ;
		if($first_created_at != null){
			$this->root = Person::where('family_id','=',$this->family_id)->where('created_at','=',$first_created_at)->get(['id'])[0]->id ;
		}

		//	$this->persons = Person::FindOrFail(6) ;
		$this->relations = Relation::where('family_id','=',$this->family_id)->get() ;

		if(Userpreferrence::where('user_id','=',\Auth::id())->exists()){
			$this->lang = Userpreferrence::where('user_id','=',\Auth::id())->get()[0]->lang ;
		}
	}

	private function add_weighted_edge($ver1,$ver2,$weight){
		$edge = $ver1->createEdgeTo($ver2) ;	 	
		$edge->setAttribute('relation',$weight) ;
		$edge->setWeight(1);
	}

	private function create_family(){
		// Create nodes for each family member
		foreach($this->persons as $id=>$value) {
	 		$this->graph->createVertex($id);
		}

		// Connect all members with relations data from DB
		foreach ($this->relations as $id => $value) {
		 	$relative_vertex = $this->graph->getVertex($value->relative_id) ;	
			$vertex1 = $this->graph->getVertex($value->person_id) ;
			$edge = $vertex1->createEdgeTo($relative_vertex) ;	 	
		 	$edge->setAttribute('relation',$value->relation) ;
		 	$edge->setWeight(1) ;

		 	// Set reverse relation too
		 	$relative_gender = $this->persons[$value->relative_id]->gender ;
		 	//$this->persons[$value->relative]["GENDER"] ;
		 	$revrelation = $this->rel_static_data->get_rev_relation($value->relation, $relative_gender);
		 	$edge = $relative_vertex->createEdgeTo($vertex1) ;	 	
		 	$edge->setAttribute('relation',$revrelation) ;
		 	$edge->setWeight(1) ;
		}

		// For each person in whole family check and add basic relation using relation-triangle
	//	$allnodes = $this->graph->getVertices();
		foreach ($this->graph->getVertices() as $node ) {
	        $connected = $this->get_neibours($node) ;

	    //    echo "<hr><h2>For this Node " , $node->getID() ," </h2> ";

	        foreach ($connected as $id ) {
	        //	echo "<h3>ID ", $id->getID(), " </h3>" ;
	    		foreach ($connected as $nxtid) {
	    		//	echo "<h3>NXT ID ", $nxtid->getID(), " </h3>" ;
	                if($id === $nxtid || $id->hasEdgeTo($nxtid)) continue ;
	                $rel1 = $id->getEdgesTo($node)->getEdgeFirst()->getAttribute('relation') ;
	                $rel2 = $node->getEdgesTo($nxtid)->getEdgeFirst()->getAttribute('relation') ;
	                $newrelation = $this->rel_static_data->get_basic_relation($rel1,$rel2);    
	            //    echo $node->getID() ," " , $nxtid->getID() , "</br>" ; 
	            //    echo $rel1 ," ",  $rel2 ," ", $newrelation ,"</br> ";
	                if($newrelation != -1){
	                //	echo " Calling add_weighted_edge ", $id->getID()," ",$nxtid->getID()," ",$newrelation ;
	                    $this->add_weighted_edge($id,$nxtid,$newrelation) ;
	                }
	            }
	        } 
		}	
	} //create_family

	// get all connected vertices
	private function get_neibours($vertex){
		$connected = array() ;
		foreach($this->graph->getVertices() as $ver) {
				 if($vertex === $ver) continue ;
				 if($vertex->hasEdgeTo($ver) ) {
				 	array_push($connected, $ver) ;
				 }
		}
		return $connected ; //array of vertices
	}

 	public function FindRelationsBetween($from, $to) {
		$vertex1 = $this->graph->getVertex($from);		
		$vertex2 = $this->graph->getVertex($to);
 		
 		//if directly connected then find relation and return
 		if($vertex1->hasEdgeTo($vertex2)) {
 			return $vertex1->getEdgesTo($vertex2)->getEdgeFirst()->getAttribute('relation') ;
 		}
 		
 		$sp_obj = new Dijkstra($vertex1) ; 
 		$myedges = $sp_obj->getEdgesTo($vertex2) ; //array of vertices on shortest path
 		//echo $idfrom ,"  " , $person[$idfrom]["NAME"] ,"  to  " , $idto , "  " ,$person[$idto]["NAME"]  ;
 		foreach ($myedges as  $value) {
 			$vertexstart =  $value->getVertexStart() ; // Get node from where Edge is starting 	
 			if($from == $vertexstart->getID()) { // check if this is start ID(from ID)
 				$relation1 = $value->getAttribute('relation') ;
 				$firstvertex = $vertexstart ;
 				continue ;
 			}
 			$relation2 = $value->getAttribute('relation') ;
 			$res_relation = $this->rel_static_data->get_rel_triangle($relation1,$relation2) ;
 			 if($to == $value->getVertexEnd()->getID()) {
 				$lastvertex = $value->getVertexEnd() ;
 				// $edge = $firstvertex->createEdgeTo($lastvertex) ;	 	
			 	// $edge->setAttribute('relation',$res_relation) ;
			 	// $edge->setWeight(1) ;
			 	return $res_relation ;
			 	// Set reverse relation too
			 	// $revrelation = get_revrelation($relation,$person[$relative]["GENDER"]);
			 	// $edge = $lastvertex->createEdgeTo($firstvertex) ;	 	
			 	// $edge->setAttribute('relation',$revrelation) ;
			 	// $edge->setWeight(1) ;
 			 } else {
 			 	$relation1 = $res_relation ;
 			 }	
 		} //foreach

 	}  //function

// 	public function GetRoot(){

// 	}

 	public function GetCYObject(){
 		# print Nodes
 		//echo 'nodes: [' ;
 		$this->cy_object = 'nodes: [' ;
        foreach($this->graph->getVertices() as $id => $node){
           //$tname = $this->persons[$id]->name ;  
			if($this->persons[$id]->name == "" &&  $this->persons[$id]->nickname == ""){
				$tname = "ANONYMOUS" . $this->persons[$id]->id ;
			}else if($this->persons[$id]->name == "" &&  $this->persons[$id]->nickname != ""){
				$tname = $this->persons[$id]->nickname ;
			} else if($this->persons[$id]->name != "" &&  $this->persons[$id]->nickname == ""){
				$tname = $this->persons[$id]->name ;
			}
			else{  // name n nickname both are not empty
				$tname = $this->persons[$id]->name . " ( " . $this->persons[$id]->nickname . " )" ;
			}
		
           $tgender = $this->persons[$id]->gender ;
           $tgeneration = $this->persons[$id]->generation ;
           if($this->persons[$id]->image != null ) {
           	$timage = $id . "f" . $this->family_id . "." . $this->persons[$id]->image ;
           }elseif($tgender == 'Male'){
           	$timage = 'man.png' ;
           }else {
           	$timage = 'woman.png' ;
           }
           
           $this->cy_object .= "{ data: { id: '$id' , name: '$tname' , gender: '$tgender' , img: '$timage' , generation: '$tgeneration' " ;
           //echo "{ data: { id: '$id' , name: '$tname' " ;
   //         if($id == $person1 || $id == $person2) {
			// //faveColor: '#EDA1ED', faveShape: 'ellipse' }
			// //echo ", faveColor: '#EDA1ED', faveShape: 'ellipse' " ;
   //         }
           $this->cy_object .= "}},";
        }    
        $this->cy_object .=  '],' ;   

        # print edges
        $this->cy_object .= 'edges: [' ;
        $i = 1 ;
        foreach($this->graph->getVertices() as $id => $node) {
        	foreach ($this->graph->getVertices() as $nxtid => $nxtnode) {
                if($id === $nxtid) continue  ;
                if(! $node->hasEdgeTo($nxtnode)) continue ;
                $name1 = $this->persons[$id]->name ;
                $name2 = $this->persons[$nxtid]->name ;
                $relation = $this->graph->getVertex($id)->getEdgesTo($nxtnode)->getEdgeFirst()->getAttribute('relation') ;
                
                //$relation = trim($relnamematrix[$relation][$lang]) ; //
                //$relation = $this->rel_static_data->get_lang_name($relation, "Hindi") ;
                $relation = $this->rel_static_data->get_lang_name($relation, $this->lang) ;
                $t_edgeid = $id . "_" . $nxtid ;
                $this->cy_object .= "{ data: { id: '$t_edgeid', weight: 1, source: '$id', target: '$nxtid' , label: '$relation' } }," ;
                // if(){

                // }

                // //faveColor: '#EDA1ED', faveShape: 'ellipse' }
                $i += 1 ;
            }
        }
        $this->cy_object .= ']' ;

 	}

 	public function check_delete($id){
 		$vertex = $this->graph->getVertex($id) ;
 		$connected = $this->get_neibours($vertex) ;
 		$deleteinfo["connector"] = False ;

 		foreach ($connected as $node) {
 			foreach ($connected as $nxtnode) {
 				if($node === $nxtnode || $node->hasEdgeTo($nxtnode)) continue ;
	 			$from = $node->getID() ;
	 			$to = $nxtnode->getID() ;
	 			$rel = $this->FindRelationsBetween($from, $to) ;
 				$basic_rel = array("Maan", "Pita", "Bhai", "Behan", "Beta", "Beti", "Pati", "Patni");
 				if(! in_array($rel, $basic_rel)) {
 					$deleteinfo["connector"] = True ;
 				}
 			}
 			$deleteinfo["neighbours"][$node->getID()] = 1;  
 		}
 		return $deleteinfo ;
 	}

 	public function splitremove($keepmember,$splitmember){
 		 $vertex1 = $this->graph->getVertex($keepmember); //from vertex
 		 $notinfamily = array($splitmember) ;
 		 foreach($this->graph->getVertices() as $id => $node) {
			if($keepmember == $node->getID()) continue;
			$vertex2 = $this->graph->getVertex($node->getID()); // member of family
	 		//if directly connected then find relation and return
	 		if($vertex1->hasEdgeTo($vertex2)) {
	 			continue ; 
	 		} 		
	 		$sp_obj = new Dijkstra($vertex1) ; 
	 		$myedges = $sp_obj->getEdgesTo($vertex2) ; //array of vertices on shortest path
	 		foreach ($myedges as  $value) {
	 			$vertexstart =  $value->getVertexStart() ;	 	
	 			if($vertexstart->getID() == $splitmember) { // if splitmember is on the way
					array_push($notinfamily,$vertex2->getID() );
					continue;
	 			}	
	 		} //foreach
 		 }
 		// return removed members IDs
 		return $notinfamily ;
 	}


 	public function getcommon($pid1,$pid2){
 		$vertex1 = $this->graph->getVertex($pid1);
		$vertex2 = $this->graph->getVertex($pid2);

		$connected1 = $this->get_neibours($vertex1);
		$connected2 = $this->get_neibours($vertex2);
		$con1 = array() ;
		$con2 = array() ;
		foreach ($connected1 as $key => $value) {
			array_push($con1,$value->getID() );
		}
		foreach ($connected2 as $key => $value) {
			array_push($con2,$value->getID() );
		}

		return array_intersect($con1, $con2) ;
 	}

 	public function get_connected_ids($pid){
 		$vertex = $this->graph->getVertex($pid);
		$connected = $this->get_neibours($vertex);
		
		$con = array() ;
		foreach ($connected as $key => $value) {
			array_push($con,$value->getID() );
		}
		return $con ;
 	}

 	public function get_member_count(){
 		return $this->member_count ;
 	}


} //class


?>












