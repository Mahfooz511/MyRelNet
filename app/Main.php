<?php  namespace App;
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

//http://localhost/MAMP/RelMap/ShowGraph.php?language=Hindi&person1=101&person2=110&form_id=993375&submit=Submit
//	require_once 'vendor/autoload.php';
//require_once "Person.php" ;
use App\Http\Requests\CreatePersonRequest;
use App\Http\Controllers\Controller;
use App\Person;
use \Fhaculty\Graph\Graph as Graph;	
use Graphp\Algorithms\ShortestPath\Dijkstra; 


 class Family  {

	private $id ;
	private $name ;
	private $graph ;
	private $persons;
	private $relations;

	private $rel_static_data ; 

	public function __construct($rel_static_data){
		$this->rel_static_data = $rel_static_data ;
		$this->load_data();
	//	$graph = new Graph;
	//	create_family();

	}

	private function load_data(){
		//$this->persons = Person::getAll() ;
	//	$this->persons = Person::FindOrFail(2) ;
		//$this->relations = Family::getAll() ;
	}

// 	private create_family(){
// 		// Create nodes for each family member
// 		foreach ($this->person as $id=>$value) {
// 	 	//$family_graph_array[$id] =  $family_graph->createVertex($id) ;
// 	 		$this->graph->createVertex($id) ;
// 		}

// 		// Connect all members with relations data from DB
// 		foreach ($this->relations as $id => $value) {
// 		 	$relation =  key($value) ;
// 		 	$relative = key($value[key($value)]) ;
// 		 	$relative_vertex = $this->graph->getVertex($relative) ;	
// 			$vertex1 = $this->graph->getVertex($id) ;
// 			$edge = $vertex1->createEdgeTo($relative_vertex) ;	 	
// 		 	$edge->setAttribute('relation',$relation) ;
// 		 	$edge->setWeight(1) ;

// 		 	// Set reverse relation too
// 		 	$revrelation = $this->rel_static_data->get_rev_relation($relation,$person[$relative]["GENDER"]);
// 		 	$edge = $relative_vertex->createEdgeTo($vertex1) ;	 	
// 		 	$edge->setAttribute('relation',$revrelation) ;
// 		 	$edge->setWeight(1) ;
// 		}

// 		// For each person in whole family check and add basic relation using relation-triangle
// 		$allnodes = $this>graph->getVertices();
// 		foreach ($this->graph->getVertices() as $node ) {
// 	        $connected = $this->graph->get_neibours($node) ;

// 	        foreach ($connected as $id ) {
// 	    		foreach ($connected as $nxtid) {
// 	                if($id === $nxtid || $id->hasEdgeTo($nxtid)) continue ;
// 	                $rel1 = $id->getEdgesTo($node)->getEdgeFirst()->getAttribute('relation') ;
// 	                $rel2 = $node->getEdgesTo($nxtid)->getEdgeFirst()->getAttribute('relation') ;
// 	                $newrelation = $this->rel_static_data->get_basic_relation($rel1,$rel2);    
// 	                if($newrelation != -1){
// 	                        add_weighted_edge($id,$nxtid,$newrelation) ;
// 	                }
// 	            }
// 	        } 
// 		}	
// 	} //create_family

// 	private function add_weighted_edge($ver1,$ver2,$weight){
// 		$edge = $ver1->createEdgeTo($ver2) ;	 	
// 		$edge->setAttribute('relation',$weight) ;
// 		$edge->setWeight(1);
// 	}

// 	private function get_neibours($vertex){
// 		$connected = array() ;
// 		foreach($this->graph->getVertices() as $ver) {
// 				 if($vertex === $ver) continue ;
// 				 if($vertex->hasEdgeTo($ver) ) {
// 				 	array_push($connected, $ver) ;
// 				 }
// 		}
// 		return $connected ;
// 	}

// 	public function CalcRelationsBetween($person1, $person2) {

// 	}

// 	public function GetRoot(){

// 	}

// 	public function GetCYObject(){

// 	}

}

class RelationMetaData {
	private $basic_triangle;
	private $rev_triangle;
	private $all_lang_name;
	private $all_rel_triangle;

	 public function __construct(){
		$this->basic_triangle["Patni"]["Pita"]     = "Maan" ;
        $this->basic_triangle["Pati"]["Maan"]     = "Pita" ;
        $this->basic_triangle["Maan"]["Beta"]     = "Patni" ;
        $this->basic_triangle["Maan"]["Beti"]     = "Patni" ;
        $this->basic_triangle["Maan"]["Bhai"]     = "Maan" ;
        $this->basic_triangle["Maan"]["Behan"]     = "Maan" ;
        $this->basic_triangle["Pita"]["Beta"]     = "Pati" ;
        $this->basic_triangle["Pita"]["Beti"]     = "Pati" ;
        $this->basic_triangle["Pita"]["Bhai"]    = "Pita";
        $this->basic_triangle["Pita"]["Behan"]     = "Pita" ;
        $this->basic_triangle["Behan"]["Behan"]     = "Behan" ;
        $this->basic_triangle["Bhai"]["Bhai"]     = "Bhai" ;
        $this->basic_triangle["Bhai"]["Behan"]     = "Bhai" ;
        $this->basic_triangle["Behan"]["Bhai"]     = "Behan" ;
        $this->basic_triangle["Bhai"]["Beta"]     = "Beta" ;
        $this->basic_triangle["Bhai"]["Beti"]     = "Beta" ;
        $this->basic_triangle["Behan"]["Beta"]     = "Beti" ;
        $this->basic_triangle["Behan"]["Beti"]     = "Beti" ;
        $this->basic_triangle["Beti"]["Pati"]     = "Beti" ;
        $this->basic_triangle["Beti"]["Patni"]     = "Beti" ;
        $this->basic_triangle["Beta"]["Pati"]     = "Beta" ;
        $this->basic_triangle["Beta"]["Patni"]     = "Beta" ;

        $this->rev_triangle["Maan"]["Male"]		=	"Beta" ;
		$this->rev_triangle["Maan"]["Female"]	=	"Beti";
		$this->rev_triangle["Pita"]["Male"]		=	"Beta";
		$this->rev_triangle["Pita"]["Female"]	=	"Beti";
		$this->rev_triangle["Beta"]["Male"]		=	"Pita";
		$this->rev_triangle["Beta"]["Female"]	=	"Maan";
		$this->rev_triangle["Beti"]["Male"]		=	"Pita";
		$this->rev_triangle["Beti"]["Female"]	=	"Maan";
		$this->rev_triangle["Bhai"]["Male"]		=	"Bhai";
		$this->rev_triangle["Bhai"]["Female"]	=	"Behan";
		$this->rev_triangle["Behan"]["Male"]	=	"Bhai";
		$this->rev_triangle["Behan"]["Female"]	=	"Behan";
		$this->rev_triangle["Pati"]["Female"]	=	"Patni";
		$this->rev_triangle["Patni"]["Male"]	=	"Pati";

	 	 $this->load_all_rel_triangle();
	 	 $this->load_all_lang_name() ;
	 }

	public function get_basic_relation($rel1, $rel2){
		if(isset($this->basic_triangle[$rel1][$rel2])) {
        	return $this->basic_triangle[$rel1][$rel2] ;
        }
        return -1 ;
	}

	public function get_rev_relation($relation,$gender){
		return($this->rev_triangle[$relation][$gender]);
	}

	private function load_all_rel_triangle(){
		$filename = 'graph_trirelation.csv' ;
	    $myfile = fopen("$filename", "r") or die("Unable to open file!");
		if($myfile) {
		    while (($line = fgets($myfile)) !== false) {
		        $myArray = explode(',', $line);
		        $this->all_rel_triangle[$myArray[0]][$myArray[1]] = trim($myArray[2]) ;
		    }
		} else {
		 	echo "Error reading file" ;
		} 
		fclose($myfile) ;
	}

	private function load_all_lang_name(){
		$filename = 'relname_alllanguage.csv' ;
        $myfile = fopen("$filename", "r") or die("Unable to open file!");

		if ($myfile) {
		    while (($line = fgets($myfile)) !== false) {
		        $myArray = explode(',', $line);
		        $this->all_lang_name[$myArray[0]]["Hindi"] = $myArray[0] ;
		        $this->all_lang_name[$myArray[0]]["English"] = $myArray[1] ;
		        $this->all_lang_name[$myArray[0]]["Urdu"] = $myArray[2] ;
		    }
		    fclose($myfile);
		} else {
			 echo "Error reading file" ;
		} 
	}

} //class

?>