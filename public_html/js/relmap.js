$(function(){ // on dom ready
  var relnet_com = 'http://'+ window.location.host +'/RelNet/public_html/' ;
  //var relnet_com = 'http://'+ window.location.host +'/' ;

  var cy = cytoscape({
  container: document.getElementById('cy'),
  
  style: cytoscape.stylesheet()
    .selector('node')
      .css({
        'content': 'data(name)',
        'background-fit': 'cover',
        'border-color': '#000',
        'border-width': 3,
        'border-opacity': 0.5
      })
    .selector('edge')
      .css({
        'target-arrow-shape': 'triangle',
        'width': 4,
        'line-color': '#ddd',
        'target-arrow-color': '#ddd',
        'content': 'data(label)'
      })
    .selector('.highlighted')
      .css({
        'background-color': '#61bffc',
        'line-color': '#61bffc',
        'target-arrow-color': '#61bffc',
        'transition-property': 'background-color, line-color, target-arrow-color',
        'transition-duration': '0.5s'
      })

      .selector('.maleedge')
      .css({
        'background-color': '#61bffc',
        'line-color': '#61bffc',
        'target-arrow-color': '#61bffc',
        'transition-property': 'background-color, line-color, target-arrow-color',
        'transition-duration': '0.5s'
      })

      .selector('.femaleedge')
      .css({
        'background-color': '#FF69B4',
        'line-color': '#FF69B4',
        'target-arrow-color': '#FF69B4',
        'transition-property': 'background-color, line-color, target-arrow-color',
        'transition-duration': '0.5s'
      })

      .selector('.queried')
      .css({
        'background-color': '#00CC00',
        'line-color': '#00CC00',
        'target-arrow-color': '#00CC00',
        'transition-property': 'background-color, line-color, target-arrow-color',
        'transition-duration': '0.5s'
      })

      .selector('.male')
      .css({
        'background-image': '../img/man.png' ,
      })
      .selector('.female')
      .css({
        'background-image': '../img/woman.png' ,
      })

      .selector('.edgeshowrelation')
      .css({
        'line-color': '#33CC33',
        'target-arrow-color': '#33CC33',
        'content': 'data(label)'
      })
      ,
  
  elements: GLOBAL_elements ,

});
   


var options = {
  name: 'breadthfirst',

  fit: true, // whether to fit the viewport to the graph
  directed: false, // whether the tree is directed downwards (or edges can point in any direction if false)
  padding: 30, // padding on fit
  circle: false, // put depths in concentric circles if true, put depths top down if false
  boundingBox: undefined, // constrain layout bounds; { x1, y1, x2, y2 } or { x1, y1, w, h }
  avoidOverlap: true, // prevents node overlap, may overflow boundingBox if not enough space
  roots: GLOBAL_root, // the roots of the trees
  maximalAdjustments: 0, // how many times to try to position the nodes in a maximal way (i.e. no backtracking)
  animate: true, // whether to transition the node positions
  animationDuration: 500, // duration of animation in ms if enabled
  ready: undefined, // callback on layoutready
  stop: function(){findonstart();}, // callback on layoutstop
};


///////////////////////
// Set images of the nodes
cy.nodes().forEach(function( ele , i , eles){
    //console.log( ele.id() );
    cy.elements('node#'+ele.id()).style({
        'background-image':  relnet_com + '/img/' + ele.data('img') 
    }) ;
}); 

// Add colors to the edges
cy.edges().forEach(function( ele , i , eles){
     var sourcenode = cy.$('#'+ele.id()).source() ;
     if(sourcenode.data('gender') == 'Male') {
        ele.addClass('maleedge');
     }
     else if(sourcenode.data('gender') == 'Female') {
        ele.addClass('femaleedge');
     }
}); 

//////////////////////
// BFS Start
var bfs = cy.elements().bfs(GLOBAL_root, function(){}, true);

bfs.path.forEach(function(ele,i,eles){
  if(bfs.path[i].isEdge()) {
    bfs.path[i].data('weight' , 100);
  }
  if(bfs.path[i].isNode()) {
    if(bfs.path[i].data('gender') == 'Male') {
      bfs.path[i].addClass('male');
    }
    if(bfs.path[i].data('gender') == 'Female') {
      bfs.path[i].addClass('female');
    }
  }
});



cy.edges().forEach(function( ele , i , eles){
    //console.log( ele.id() );
    if(ele.data('weight') < 10 ){
      ele.hide();
    }
});

var i = 0;
var highlightNextEle = function(){
  //bfs.path[i].addClass('highlighted');
  if( i < bfs.path.length ){
    i++;
    //setTimeout(highlightNextEle, 100);
    setTimeout(highlightNextEle, 0);
  }
};

// kick off first highlight
highlightNextEle();

// BFS end


var gridoptions = {
  name: 'grid',

  fit: true, // whether to fit the viewport to the graph
  padding: 30, // padding used on fit
  boundingBox: undefined, // constrain layout bounds; { x1, y1, x2, y2 } or { x1, y1, w, h }
  avoidOverlap: true, // prevents node overlap, may overflow boundingBox if not enough space
  rows: undefined, // force num of rows in the grid
  columns: undefined, // force num of cols in the grid
  position: function( node ){}, // returns { row, col } for element
  sort: undefined, // a sorting function to order the nodes; e.g. function(a, b){ return a.data('weight') - b.data('weight') }
  animate: true, // whether to transition the node positions
  animationDuration: 500, // duration of animation in ms if enabled
  ready: undefined, // callback on layoutready
  stop: undefined // callback on layoutstop
};

var circleoptions = {
  name: 'circle',

  fit: true, // whether to fit the viewport to the graph
  padding: 30, // the padding on fit
  boundingBox: undefined, // constrain layout bounds; { x1, y1, x2, y2 } or { x1, y1, w, h }
  avoidOverlap: true, // prevents node overlap, may overflow boundingBox and radius if not enough space
  radius: undefined, // the radius of the circle
  startAngle: 3/2 * Math.PI, // the position of the first node
  counterclockwise: false, // whether the layout should go counterclockwise (true) or clockwise (false)
  sort: undefined, // a sorting function to order the nodes; e.g. function(a, b){ return a.data('weight') - b.data('weight') }
  animate: false, // whether to transition the node positions
  animationDuration: 500, // duration of animation in ms if enabled
  ready: undefined, // callback on layoutready
  stop: undefined // callback on layoutstop
};

var spreadoptions = {
  name: 'spread',

  animate: true, // whether to show the layout as it's running
  ready: undefined, // Callback on layoutready
  stop: undefined, // Callback on layoutstop
  fit: true, // Reset viewport to fit default simulationBounds
  minDist: 20, // Minimum distance between nodes
  padding: 20, // Padding
  expandingFactor: -1.0, // If the network does not satisfy the minDist
  // criterium then it expands the network of this amount
  // If it is set to -1.0 the amount of expansion is automatically
  // calculated based on the minDist, the aspect ratio and the
  // number of nodes
  maxFruchtermanReingoldIterations: 50, // Maximum number of initial force-directed iterations
  maxExpandIterations: 4, // Maximum number of expanding iterations
  boundingBox: undefined // Constrain layout bounds; { x1, y1, x2, y2 } or { x1, y1, w, h }
};

var randomoptions = {
  name: 'random',

  fit: true, // whether to fit to viewport
  padding: 30, // fit padding
  boundingBox: undefined, // constrain layout bounds; { x1, y1, x2, y2 } or { x1, y1, w, h }
  animate: false, // whether to transition the node positions
  animationDuration: 500, // duration of animation in ms if enabled
  ready: undefined, // callback on layoutready
  stop: undefined // callback on layoutstop
};

var concentricoptions = {
  name: 'concentric',

  fit: true, // whether to fit the viewport to the graph
  padding: 30, // the padding on fit
  startAngle: 3/2 * Math.PI, // the position of the first node
  counterclockwise: false, // whether the layout should go counterclockwise/anticlockwise (true) or clockwise (false)
  minNodeSpacing: 10, // min spacing between outside of nodes (used for radius adjustment)
  boundingBox: undefined, // constrain layout bounds; { x1, y1, x2, y2 } or { x1, y1, w, h }
  avoidOverlap: true, // prevents node overlap, may overflow boundingBox if not enough space
  height: undefined, // height of layout area (overrides container height)
  width: undefined, // width of layout area (overrides container width)
  concentric: function(node){ // returns numeric value for each node, placing higher nodes in levels towards the centre
    return node.degree();
    // return (node.data('generation') * -1);
  },
  levelWidth: function(nodes){ // the variation of concentric values in each level
    //return nodes.maxDegree() / 4;
    return 20;
  },
  animate: true, // whether to transition the node positions
  animationDuration: 500, // duration of animation in ms if enabled
  ready: undefined, // callback on layoutready
  stop: undefined // callback on layoutstop
};

var oldestgen ; 
var presetoptions = {
  name: 'preset',

  positions: //undefined,
    function(node){  
                var row_multiplier = node.data('generation') - oldestgen + 1 ;//nodetree[node.id()] ;
                //console.log("IN PRESET " + node.id() , node.data('generation') , row_multiplier);
                 var posx = node.position('x');
                 //console.log(posx , "  NODPOS x");
                var position = { x: posx , y: 90 * row_multiplier} ;
                return position ; },
  // map of (node id) => (position obj); or function(node){ return somPos; }
  avoidOverlap: true,
  minNodeSpacing: 50,
  zoom: undefined, // the zoom level to set (prob want fit = false if set)
  pan: undefined, // the pan level to set (prob want fit = false if set)
  fit: false, // whether to fit to viewport
  padding: 30, // padding on fit
  animate: false, // whether to transition the node positions
  animationDuration: 500, // duration of animation in ms if enabled
  ready: undefined, // callback on layoutready
  stop: undefined // callback on layoutstop
};


cy.layout( options );
// cy.viewport({
//   zoom: 2,
//   pan: { x: 100, y: 100 }
// });
cy.maxZoom( 2 );
cy.minZoom( 0.3 );

if (typeof GLOBAL_p1 !== 'undefined') {
    cy.add({
      group: "edges",
      data: { id: GLOBAL_p1+"_"+GLOBAL_p2 , weight: 100, source: GLOBAL_p1 , target: GLOBAL_p2, label: GLOBAL_rel  }
    });
    cy.$(GLOBAL_edge).addClass('queried');
    cy.$(GLOBAL_edge).show();
}

function findmember(memberid){
      var j = cy.$("#"+memberid);
      cy.animate({
        fit: {
          eles: j,
          padding: 20
        }
      }, {
        duration: 1000
      });   
}

// Ajax call to find relation name b/w two members
function get_findrelation(family_id,from,to){
    $.get(relnet_com + "findrelation",{fid: family_id, rfid1: from, rfid2: to},
      function(data){          
       var relation = data.replace(/(\r\n|\n|\r)/gm,"") ;
       if(relation != "SELF") showrelation(from,to,relation);        
      }
    );
}

// drwa relation edge on graph for 2 members
function showrelation(nodeid1,nodeid2,edgelabel){
  //console.log(nodeid1,nodeid2,edgelabel);
  var edgeid = nodeid1 + "_" + nodeid2 ;
  var j = cy.$("#"+edgeid);
  //console.log("J "+j.size());
  if(j.size() == 0){
    cy.add({
      group: "edges",
      data: { id: edgeid, weight: 100, source: nodeid1, target: nodeid2 , label: edgelabel },
    });
  }else{
    j.show();
  }

  cy.elements('edge#'+edgeid).style({
        'line-color': '#33CC33', 'target-arrow-color': '#33CC33' 
  });

}


// find member show on graph (after popup form )
$("#submit").click(function(){    
  var memberid = $( "#membersfind option:selected" ).val(); 
  $("#lightbox_m").hide();
  if ( $( "#relationsfind" ).length ) { 
    var memberid2 = $( "#relationsfind option:selected" ).val(); 
    get_findrelation(family_id,memberid,memberid2) ;
  } else {
    findmember(memberid);
  }
 
}); 

//Toolbar click
$("#toolbar a").on("click touchend",function(){
  event.preventDefault();
  var myitem = $(this).attr("id")  ;
  if(myitem == "showall"){
    cy.edges().show() ;
  }
  if(myitem == "hideall"){
    cy.edges().hide() ;
  }
  if(myitem == "showmin"){    
    cy.edges().forEach(function( ele , i , eles){
        if(ele.data('weight') < 10 ){
          ele.hide();
        }
        else {
          ele.show();
        }
    });
  }
});

// Toolbar Layout change
$("#graphlayout a").on("click touchend",function(){
  event.preventDefault();
  // console.log("HEY " + $(this).attr("id") );
  var myitem = $(this).attr("id")  ;
  if(myitem == "layout1"){
    cy.layout(gridoptions);
  }
  if(myitem == "layout2"){
    cy.layout(circleoptions);
  }
  if(myitem == "layout3"){
    cy.layout(spreadoptions);
  }
  // if(myitem == "layout4"){
  //   cy.layout(springyoption);
  // }
  if(myitem == "layout5"){
    cy.layout(randomoptions);
  }
  if(myitem == "layout6"){
    // BFS (ShowMin)
    cy.edges().forEach(function( ele , i , eles){
        if(ele.data('weight') < 10 ){
          ele.hide();
        }
        else {
          ele.show();
        }
    });
  }
  if(myitem == "layout7"){ // OldOnTop    
    
    var nodes = cy.nodes().sort(function( a, b ){
      // console.log(a.data('generation') , b.data('generation') );
      return a.data('generation') < b.data('generation');
    });

    oldestgen = nodes[0].data('generation');
    cy.edges().hide();
    // use BFS to show edges starting from top
    bfs2 = cy.elements().bfs("#"+nodes[0].id(), function(){}, true);
    bfs2.path.forEach(function(ele,i,eles){
      if(bfs2.path[i].isEdge()) {
        bfs2.path[i].show();
        bfs2.path[i].addClass('highlighted');
      }
    });
    // Or use DFS to show edges starting from top
    // dfs = cy.elements().dfs("#"+nodes[0].id(), function(){}, true);
    // dfs.path.forEach(function(ele,i,eles){
    //   if(dfs.path[i].isEdge()) {
    //     dfs.path[i].show();
    //     dfs.path[i].addClass('highlighted');
    //   }
    // });
    cy.layout(presetoptions); 
   }

});


// to be run once on page load
function findonstart(){
      if(typeof findmem != 'undefined') {
        findmember(findmem);
      }
      if(typeof rfid1 != 'undefined' && typeof rfid2 != 'undefined'){
        get_findrelation(family_id,rfid1,rfid2) ;
      }
}
findonstart();

}); // on dom ready



