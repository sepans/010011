    // CONSTANTS

	var MAX_RESULTS_PER_BOX = 15;
	var MAX_SEARCH_RESULTS = 75;

	var WINDOW_WIDTH = 250;
	var WINDOW_HEIGHT = 300;

	var WRITE_WINDOW_WIDTH = 400;
	var WRITE_WINDOW_HEIGHT = 250;
	
	var TIMING_HINT_IMAGE = 5000; // 5 seconds
	var TIMING_HINT_IMAGE_FADEOUT = 3000; // 5 seconds

	var TIMING_HINT_IMAGE_ONSCREEN = 5000;

	var TIMING_TYPEIN_WORD = 3000; // 3 seconds

	var TIMING_HINT_MESSAGE_1 = 15000; // 3 seconds
	
	var HINT_MESSAGE_1 =[ 'well, waiting got you this far, but not much further, so select one of the text excerpts that look like they could be interesting.'
                        ,'ok, find a text that looks like it could be interesting. click on it.'
                        ,'which of the excerpts above look promising to you? click on it.'];


    var HINT_MESSAGE_2 =[ "this is the paragraph which hosted the excerpt.."+
                    "now, find another text. if theres nothing that looks attractive, try scrolling through any of the columns of examples til you find one and click on that.",
                    "find another text fragment that looks promising. click.",
                    "now click on another text that seems interesting."];

	var HINT_MESSAGE_3 =[ "if either of these new text windows need to be moved, just grab the L (ikon) in the upper left of the text window and move it."+
                    "anyway, now in these two texts you'll see the original searchterm (\'search\', remember), underlined. click and drag one ‘search’ to the other. a blue line and a blue text. a synthesis of the two."];

	var HINT_MESSAGE_4 =[ "also at the top of this text area, to the right, you’ll find the title of the work from which this paragraph was taken. a click on that will bring up a larger excerpt of the text,"+
	" but before you do that, below the author and title is a line with a slider. click the crossbar and slide to the right. various words will be highlighted gray. it calculates relevance. the further right,"+
	" the larger the scope of relevance. link any two highlighted words from any two texts. red line and text. link red and red or blue, green text."+
    "the synonyms and wikipedia and twitter associations you dont need me for."];


    var ELIZA_MESSAGES = ["does %word interest you?","what does %word suggest to you?","is %word much on your mind?","do you feel strongly discussing about %word?"];
	
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
    