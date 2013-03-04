    // CONSTANTS

	var MAX_RESULTS_PER_BOX = 15;
	var MAX_SEARCH_RESULTS = 75;

	var WINDOW_WIDTH = 250;
	var WINDOW_HEIGHT = 300;

	var WRITE_WINDOW_WIDTH = 400;
	var WRITE_WINDOW_HEIGHT = 250;
	
	var BY_TERM_JSON = '/proxy.php?proxy_url='+'http://wikipedia-miner.cms.waikato.ac.nz/services/exploreArticle?outLinks=true%26parentCategories=true%26linkRelatedness=true%26responseFormat=json%26title=';

	var THES_JSON = '/proxy.php?proxy_url=http://words.bighugelabs.com/api/2/173aaf6b8cc559ac582b75f5ef7a8c8c/';
	
	var WIKI_API_URL = '/proxy.php?proxy_url='+'http://en.wikipedia.org/w/api.php?action=query%26prop=revisions%26rvprop=content%26format=json%26pageids=';


	var BLUE_ENDPOINT_OPTS = { endpoint:"Rectangle",
			paintStyle:{ width:25,height: 20,  fillStyle:'#555' },
			isSource:true,
			connectorStyle : { strokeStyle:"#00F" },
			isTarget:true, 
			anchor: "TopCenter"
			};
	var RED_ENDPOINT_OPTS = { endpoint:"Rectangle",
			paintStyle:{ width:25,height: 20,  fillStyle:'#555' },
			isSource:true,
			connectorStyle : { strokeStyle:"#F00" },
			isTarget:true, 
			anchor: "TopCenter"
			};
	
	var GREEN_ENDPOINT_OPTS = { endpoint:"Rectangle",
			paintStyle:{ width:25,height: 20,  fillStyle:'#555' },
			isSource:true,
			connectorStyle : { strokeStyle:"#00FF00" },
			isTarget:true, 
			anchor: "TopCenter"
			};


    // UTIL FUNCTIONS

	function sortJSON(data,sortMethod) {
		var ret;
		if(data.outLinks==undefined)
		    return null;
		if(sortMethod=='relatedness') {
			ret=data.outLinks.sort(function(a,b) { return parseFloat(b.relatedness) - parseFloat(a.relatedness) } );
		}
		else {
			ret=data.outLinks.sort(function(a,b) { return a.title > b.title ? 1 : -1 } );
		}
		return ret;
	}
	
	function checkRegexp( o, regexp, n ) {
            if ( !( regexp.test( o.val() ) ) ) {
             //   o.addClass( "ui-state-error" );
              //  updateTips( n );
                return false;
            } else {
                return true;
            }
    }
    
    
	function scrollToAnchor(aid, scroll_container,container_id){
    	var path ="#"+container_id+" a[name='"+ aid +"']";
    	var aTag = $(path);
	    scroll_container.animate({scrollTop: aTag.offset().top-80},'fast');
	}
    
    window.navigator.sayswho= (function(){
      var N= window.navigator.appName, ua= window.navigator.userAgent, tem;
      var M= ua.match(/(opera|chrome|safari|firefox|msie)\/?\s*(\.?\d+(\.\d+)*)/i);
      if(M && (tem= ua.match(/version\/([\.\d]+)/i))!= null) M[2]= tem[1];
      M= M? [M[1], M[2]]: [N, window.navigator.appVersion,'-?'];
      return M;
    })();
    