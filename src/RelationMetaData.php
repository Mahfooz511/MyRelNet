<?php namespace RelNet ;

class RelationMetaData {
	private $basic_triangle; // basic relations triangle
	private $rev_triangle; // reverse relations
	private $all_lang_name; // relations in all languages
	private $all_rel_triangle; // all relations traingle

	public function __construct(){
		// [AB][BC] = AC
		// If A is Patni of B, B is Pita of C, then A is Maan of C
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
        $this->basic_triangle["Beta"]["Maan"]     = "Bhai" ;
        $this->basic_triangle["Beta"]["Pita"]     = "Bhai" ;
        $this->basic_triangle["Beti"]["Maan"]     = "Behan" ;
        $this->basic_triangle["Beti"]["Pita"]     = "Behan" ;

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
		$filename = '../src/graph_trirelation.csv' ;
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
		$filename = '../src/relname_alllanguage.csv' ;
        $myfile = fopen("$filename", "r") or die("Unable to open file!");

		if ($myfile) {
		    while (($line = fgets($myfile)) !== false) {
		        $myArray = explode(',', $line);
		        $this->all_lang_name[$myArray[0]]["Hindi"] = $myArray[0] ;
		        $this->all_lang_name[$myArray[0]]["English"] = $myArray[1] ;
		        $this->all_lang_name[$myArray[0]]["Urdu"] = trim($myArray[2]) ;
		    }
		    fclose($myfile);
		} else {
			 echo "Error reading file" ;
		} 
	}

	public function get_lang_name($relation, $lang){
		return $this->all_lang_name[$relation][$lang] ;
	}

	public function get_rel_triangle($rel1,$rel2){
		return $this->all_rel_triangle[$rel1][$rel2] ;
	}

} //class

?>