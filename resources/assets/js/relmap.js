$(function(){ // on dom ready
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
        'background-image': 'man.png' ,
      })
      .selector('.female')
      .css({
        'background-image': 'woman.png' ,
      }),
  
  elements: GLOBAL_elements ,
  
  layout: { 
    name: 'breadthfirst',
//    directed: true,
    roots: GLOBAL_root ,
    avoidOverlap: true,
//    padding: 30
  },

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
  stop: undefined // callback on layoutstop
};

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
  bfs.path[i].addClass('highlighted');
  if( i < bfs.path.length ){
    i++;
    //setTimeout(highlightNextEle, 100);
    setTimeout(highlightNextEle, 0);
  }
};

// kick off first highlight
highlightNextEle();

//cy.$(GLOBAL_p1).addClass('queried');
//cy.$(GLOBAL_p2).addClass('queried');
cy.add({
    group: "edges",
    data: { id: GLOBAL_p1+"_"+GLOBAL_p2 , weight: 100, source: GLOBAL_p1 , target: GLOBAL_p2, label: GLOBAL_rel  }
});
cy.$(GLOBAL_edge).addClass('queried');
cy.$(GLOBAL_edge).show();


}); // on dom ready