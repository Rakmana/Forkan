
// usage: log('inside coolFunc', this, arguments);
// paulirish.com/2009/log-a-lightweight-wrapper-for-consolelog/
window.log = function(){
  log.history = log.history || [];   // store logs to an array for reference
  log.history.push(arguments);
  if(this.console) {
    arguments.callee = arguments.callee.caller;
    var newarr = [].slice.call(arguments);
    (typeof console.log === 'object' ? log.apply.call(console.log, console, newarr) : console.log.apply(console, newarr));
  }
};

// make it safe to use console.log always
(function(b){function c(){}for(var d="assert,count,debug,dir,dirxml,error,exception,group,groupCollapsed,groupEnd,info,log,timeStamp,profile,profileEnd,time,timeEnd,trace,warn".split(","),a;a=d.pop();){b[a]=b[a]||c}})((function(){try
{console.log();return window.console;}catch(err){return window.console={};}})());


// Load the application once the DOM is ready, using `jQuery.ready`:
$(function(){
 
    var cfg ={};
	cfg = {
			ApiServer  : "http://127.0.0.1/forkan/api/",
			version    : "2.0",
			key        : "28e336ac6c9423d946ba02d19c6a2632",
			riwaya     : "1", // riwaya  [1:hafs 2:warch ...]
			tafseer    : "1", // Tafseer [1:tabari 2:jalalyn ...] 
			telawa     : "1", // Telawa  [1:Sudais 2:Afasy ...] 
			StartPage   : "50",
			NavigationMethod: 0,//[0: (d) Navigation with Quran page(604p), 1: custome per ayas]
            url : function(){
                return /*'http://localhost/forkan/xplorer/'*/this.ApiServer + this.version +'/'+ this.key ;
            }
			};  

			
//#############################################################################
//-------------------------------------- Aya ----------------------------------
  
// Aya Model
  var modAya = Backbone.Model.extend({
  // Our basic **Aya** model has [index, sura, aya, text, riwaya, audio  and views] attributes.
    defaults: {
      //yid   : 0,
      //sur   : 0,
      snm   : 0,//sura name (from suras)
      //aya   : 0,
      //txt   : '',
      rwy   : 0,/*,
	  audio  : '',
	  views  : 0,*/
	  active : false
    },
	// index
	idAttribute: "yid",
    initialize: function() {
	  if(_.isUndefined(this.get('yid'))){ 
		//this.destroy();
		//alert('empty');
      }
	  else{
	  //--- make attr more readable then "array[x]
	    this.set({yid : this.get("yid"),
	            sur  : this.get("sur"),
	            aya   : this.get("aya"),
	            txt  : this.get("txt")
	            //"riwaya": this.get("rw")
				});
				
		//alert(JSON.stringify(this));
		}
    },

    focus: function() {
      this.set({"active" :true});
    },
    blur: function() {
      this.set({"active" :true});
    },
    clear: function() {
      this.destroy();
    }

  });

// Ayas Collection
  var colAyas = Backbone.Collection.extend({

    // Reference to this collection's model.
    model: modAya,
	  
	/*url: function(){
        //var arg={cls:"q",act:"get",ayaID:1,nbr:17};
		return "/aya";
	},*/

	parse: function(response) {
       return response.dt;
    },
    // Filter down the list of all todo items that are finished.
    /*done: function() {
      return this.filter(function(todo){ return todo.get('done'); });
    },*/



    // Ayas are sorted by their original insertion order.
    comparator: function(mod) {
      return mod.get('order');
    }

  });

// Aya Item View
  var viewAya = Backbone.View.extend({

    //... is a list tag.
    tagName:  "span",
    className:"iAyaCon",
	//el: $(".iAya"),
	
    // Cache the template function for a single item.
    template: _.template($('#aya-template').html()),
    SideTemplate : _.template($('#side-template').html()),

    // The DOM events specific to an item.
    events: {
      "hover .iAya"                 : "iHover",
      "click .iAya"                 : "iFocus"
    },
	
    initialize: function() {
      _.bindAll(this, 'render', 'close'/*, 'remove'*/);
      this.model.bind('change', this.render);
      this.model.bind('destroy', this.remove);

    },

    // Re-render the contents of the todo item.
    render: function() {
		var aya = this;
		
		/*var pg = Forkan.Pages.find(function(page){
			(page.get('pid') == aya.model.get('sur')) { aya.model.set({'snm':sura.get('nam')});}
		return (sura.get('sid') == aya.model.get('sur'));});*/
		
		aya.model.set({'snm':Forkan.Suras.get(aya.model.get('sur')).get('nam')});
		//aya.model.set({'pid':Forkan.Pages.get(aya.model.get('sur')).get('nam')});
			
      $(this.el).html(this.template(this.model.toJSON()));
      return this;
    },

    // On Hover on Aya.
    iHover: function() {
      $(this.el).find(".iAya").toggleClass("ayaHover");
      
    },

    // On click or focus on Aya.
    iFocus: function() {
		$(".iAya").removeClass("ayaActive");
		$(this.el).find(".iAya").addClass("ayaActive");
		//$("#iside").html(this.SideTemplate(this.model.toJSON()));
		
		var aya = this;
		
		// change currents breadcrumbe
		Forkan.Suras.get(aya.model.get('sur')).active();
		
		//--- Show Tafseer
		$("#iTafseerCont").empty().prepend(
                new viewTafseer({model: Forkan.Tafseers.get(aya.model.get('yid'))}).render().el);

    },


    close: function() {
        $(this.el).unbind();
        $(this.el).empty();
    },
    // Remove the item, destroy the model.
    clear: function() {
      this.model.clear();
    }

  });

// maybe add AyasView 4 search result  

// Ayas List View
  var viewAyas = Backbone.View.extend({
    el: $('#ipage'),
    
    initialize: function() {
        this.model.bind("reset", this.render, this);
        this.model.bind("fetch", this.render, this);
    },
    render: function(eventName) {
        //$(this.el).empty();
		_.each(this.model.models, function(iModel) {
			
            //TODO: add cfg 4: append vs prepend
			
			if(!_.isUndefined(iModel.get('yid'))){
			  $(this.el).prepend(
                new viewAya({model: iModel}).render().el);
			}
        }, this);
		//this.last.
		//$('.iAya').first().click();
        return this;
    }
});

			
//#############################################################################
//-------------------------------------- Tafseer ----------------------------------
  
// Tafseer Model
  var modTafseer = Backbone.Model.extend({
  // Our basic **Aya** model has [index, sura, aya, text, riwaya, audio  and views] attributes.
    defaults: {
      //yid   : 0,
      //sur   : 0,
      snm   : 0,//sura name (from suras)
      //aya   : 0,
      //txt   : '',
      rwy   : 0,/*,
	  audio  : '',
	  views  : 0,*/
	  active : false
    },
	// index
	idAttribute: "tid",
    initialize: function() {
	  if(_.isUndefined(this.get('tid'))){ 
		//this.destroy();
		//alert('empty');
      }
	  else{
	  //--- make attr more readable then "array[x]
	    this.set({tid : this.get("tid"),
	            sur  : this.get("sur"),
	            aya   : this.get("aya"),
	            txt  : this.get("txt")
	            //"riwaya": this.get("rw")
				});
				
		//alert(JSON.stringify(this));
		}
    },

    focus: function() {
      this.set({"active" :true});
    },
    blur: function() {
      this.set({"active" :true});
    },
    clear: function() {
      this.destroy();
    }

  });

// Tafseer Collection
  var colTafseers = Backbone.Collection.extend({

    // Reference to this collection's model.
    model: modTafseer,

	parse: function(response) {
       return response.dt;
    },

    // Ayas are sorted by their original insertion order.
    comparator: function(mod) {
      return mod.get('order');
    }

  });

// Tafseer Item View
  var viewTafseer = Backbone.View.extend({

    //... is a list tag.
    tagName:  "span",
    //className:"iTafseerCon",
	//el: $("#iTafseerCont"),
	
    // Cache the template function for a single item.
    template: _.template($('#tafseer-template').html()),

    // The DOM events specific to an item.
    events: {
      //"hover .iAya"                 : "iHover",
      //"click .iAya"                 : "iFocus"
    },
	
    initialize: function() {
      _.bindAll(this, 'render', 'close'/*, 'remove'*/);
      this.model.bind('change', this.render);
      this.model.bind('destroy', this.remove);

    },

    // Re-render the contents of the todo item.
    render: function() {

      $(this.el).html(this.template(this.model.toJSON()));
      return this;
    },


    close: function() {
        $(this.el).unbind();
        $(this.el).empty();
    },
    // Remove the item, destroy the model.
    clear: function() {
      this.model.clear();
    }

  });

// maybe add AyasView 4 search result  

// Ayas List View
/*  var viewTafseers = Backbone.View.extend({
    el: $('#ipage'),
    
    initialize: function() {
        this.model.bind("reset", this.render, this);
        this.model.bind("fetch", this.render, this);
    },
    render: function(eventName) {
        //$(this.el).empty();
		_.each(this.model.models, function(iModel) {
			
            //TODO: add cfg 4: append vs prepend
			
			if(!_.isUndefined(iModel.get('yid'))){
			  $(this.el).prepend(
                new viewAya({model: iModel}).render().el);
			}
        }, this);
		//this.last.
		//$('.iAya').first().click();
        return this;
    }
});
*/

//#############################################################################
//---------------------------------- Pages ------------------------------------

// Page Item Model
var modPage = Backbone.Model.extend({
    //urlRoot: cfg.url()+"/page",
    defaults: {
        "pid": 0,
        "sur":  "",
        "aya":  ""
      }, 
	// index
	idAttribute: "pid",
	  
	initialize: function() {
      //if (!this.get("index")) {
		//this.destroy();
      //};
	  //--- make attr more readable then "array[x]
	  this.set({"pid" : this.get("pid"),
	            "sur"  : this.get("sur"),
	            "aya"   : this.get("aya")
				});
				
		//alert(JSON.stringify(this));
    }
});

// Page Item View
var viewPage = Backbone.View.extend({
 
    tagName: "li",
 
    template: _.template($('#page-template').html()),
 
    events: {
      "click .iPage"                 : "select"
    }, 
    initialize: function() {
        this.model.bind("change", this.render, this);
        this.model.bind("destroy", this.close, this);
    },
 
    render: function(eventName) {
        $(this.el).html(this.template(this.model.toJSON()));

        return this;
    },
 
 
    select: function() {
        var page = this.model; 
		this.active();
        Forkan.navigate("ayas/page/"+page.get("pid"), true);
    },
    active: function() {
        var page = this.model; 
        $('#activePage').html(page.get('pid'));
    },
	
    close: function() {
        $(this.el).unbind();
        $(this.el).remove();
    }
});


// Pages List Collection
var colPages = Backbone.Collection.extend({
    model : modPage,
    url : function(){
        return 'ayas/page';
    },
	parse: function(response) {
       return response.dt;
    }
});

// Pages List View
var viewPages = Backbone.View.extend({
    el: $('#pageList'),
    
    initialize: function() {
        this.model.bind("reset", this.render, this);
    },
    render: function(eventName) {
        _.each(this.model.models, function(iModel) {
            //TODO: add cfg 4: append vs prepend
			$(this.el).prepend(
                new viewPage({model: iModel}).render().el);
        }, this);
        return this;
    }
});

 
//############################################################################# 
//-------------------------------- Suras Collection ---------------------------

// Sura Item Model
  var modSura = Backbone.Model.extend({
  //[start, ayas, order, rukus, name, tname, ename, type] attributes.
  //[0, 7, 5, 1, 'الفاتحة', "Al-Faatiha", 'The Opening', 'Meccan'],

    defaults: {
     /*  sid  : 0,
      sta  : 0,
      ays   : 0,
      ord  : 0,
      rukus  : 0,
      nam   : '',
	  tnm  : '',
	  enm  : '',
	  typ   : '',
      audio  : ''*/
    },
	// index
	idAttribute: "sid",

    initialize: function() {
	  if(_.isUndefined(this.get('0'))){ 
		//this.destroy();
		//alert('empty');
      }
	  else{
	  //--- make attr more readable then "array[x]
		this.set({"sta" : this.get("1"),
	            "sid"  : this.get("0"),
	            "ays"  : this.get("2"),
	            "ord" : this.get("3"),
	            //"rukus" : this.get("rukus"),
	            "nam"  : this.get("5"),
	            //"tname" : this.get("tname"),
	            "enm" : this.get("4"),
	            "typ"  : this.get("6")
	            //"audio" : this.get("audio")
	            //"riwaya": this.get("rw")
				});
				
		//alert(JSON.stringify(this));
		//alert(JSON.stringify(this.get('5')));
		}
    },
    active: function(){
	    $('#activeSura').html(this.get('nam'));
	},
    // Remove this Todo from *localStorage* and delete its view.
    clear: function() {
      this.destroy();
    }

  });

// Suras List Collection
  var colSuras = Backbone.Collection.extend({

    model: modSura,
	  
	/*url: function(){
		return '/sura';//+cfg.StartAya+"/nbr=15";
	},*/

	parse: function(response) {
       return response.dt;
    }

  });


// Sura Item View
  var viewSura = Backbone.View.extend({
 
    tagName: "li",
    className:"iSuraCon",
 
    template: _.template($('#sura-template').html()),

    events: {
      "click .iSura"                 : "select"
    }, 
	
    initialize: function() {
        this.model.bind("change", this.render, this);
        this.model.bind("destroy", this.close, this);
    },
 
    render: function(eventName) {
        $(this.el).html(this.template(this.model.toJSON()));
        return this;
    },
 
    select: function() {
        var sura = this.model; 
		this.active();
        Forkan.navigate("ayas/"+(sura.get("sta"))+'/to/'+sura.get("ays"), true);
    },
    active: function() {
        var sura = this.model; 
        $('#activeSura').html(sura.get('nam'));
    },
 	
    close: function() {
        $(this.el).unbind();
        $(this.el).remove();
    }
    



  });

// Sura List View
  var viewSuras = Backbone.View.extend({
    el: $('#suraList'),
    
    initialize: function() {
        this.model.bind("reset", this.render, this);
        this.model.bind("active", this.active, this);
    },
    render: function(eventName) {
		$(this.el).empty();
        _.each(this.model.models, function(iModel) {
            //TODO: add cfg 4: append vs prepend
			if(!_.isUndefined(iModel.get('sid'))){
			$(this.el).prepend(
                new viewSura({model: iModel}).render().el);
			}
        }, this);
        return this;
    },
    active: function() {
        var sura = this.model; 
        $('#activeSura').html(sura.get('nam'));
    }
});
  
  
  // The Application
  // ---------------
  var AppView = Backbone.View.extend({

    el: $("#forkanApp"),
    events: {
      //"keypress #new-todo":  "createOnEnter",
      //"keyup #new-todo":     "showTooltip",
      //"click .iSura": "GotoSura",
      //"click .mark-all-done": "toggleAllComplete"
    },

    initialize: function() {
      
    },

    // Re-rendering the App just means refreshing the statistics -- the rest
    // of the app doesn't change.
    render: function() {
	  
            $("*[rel=twipsy]").tooltip({
               //live: true
            });
			$("*[rel=popover]")
                .popover({
                  offset: 10
                })
                .click(function(e) {
                  e.preventDefault()
                });

			$('.dropdown-toggle').dropdown();
			
	}



  });



 
var AppRouter = Backbone.Router.extend({
 
    routes: {
        "ayas/page/:id"     : "getAyasPerPage",
        //"page"              : "getPages",
        //"tafseer"           : "getTafseers",
        ""                  : "home",
    },
	init: function(){
        var self = this;
		// initialize All object
		
        this.Ayas   = new colAyas();
        this.Tafseers=new colTafseers();
        this.Suras  = new colSuras();
		this.Suras.fetch({
			url : cfg.ApiServer + cfg.version +'/'+ cfg.key+'/suras',
			success: function() {
		    	self.SurasView = new viewSuras({model: self.Suras});
				self.SurasView.render();
		$('.scroller').jScrollPane();
			}
		});
		Forkan.Ayas.bind('add',     this.irender);
		Forkan.Ayas.bind('reset',   this.irender);
		Forkan.Ayas.bind('all',     this.irender);
      
		Forkan.Suras.bind('add',     this.irender);
		Forkan.Suras.bind('reset',   this.irender);
		Forkan.Suras.bind('all',     this.irender);
		

		
        this.Pages  = new colPages();
		this.Pages.fetch({
			url : cfg.ApiServer + cfg.version +'/'+ cfg.key+'/pages',
			success: function() {
			    
		    	self.PagesView = new viewPages({model: self.Pages});
				self.PagesView.render();
				Backbone.history.start();
				//if (self.requestedId) self.getAya(self.requestedId);
				$('#pg'+cfg.startPage).click();
		$('.scroller').jScrollPane();
				
			}
		});
		
	  
	  
	},    

	irender: function() {
	  
            //$(".twipsy").tooltip({
               //live: true
            //});
			$("*[rel=popover]")
                .popover({
                  offset: 10
                })
                .click(function(e) {
                  e.preventDefault()
                });


			$('.dropdown-toggle').dropdown();
			
	
			
			
	},
 
    home: function() {
        this.getAyasPerPage(cfg.StartPage);
 
    },
    
    getAyas: function(id,nbr) {
		var self = this;
		self.requestedId = id;
		//if (!this.Ayas){this.init();}
              
		$("#ipage").animate({right: '-500px'},"700",function() { $(this).hide().empty() });


         this.Ayas.fetch({
				url : cfg.url()+"/ayas/"+id+"/to/"+nbr,
                success: function() {
				//$("#ipage").slideDown();				
				$("#ipage").animate({right: '0px'},"700",function() { $(this).show() });
		    	/* ; //self.AyasView = new viewAyas({model: self.Ayas});
				//self.AyasView.render();
                    //if (self.requestedId) self.wineDetails(self.requestedId);*/
            }
        });

    } , 
	
    getAyasPerPage: function(id) {
		var self = this;
		// TODO: add validator for page existance
		if(!self.Pages.get(id)){
		//<a class="close" data-dismiss="modal">×</a>
			$('<div class="modal"><div class="modal-header">    <h3>تنبيه</h3></div> <div class="modal-body"><p> لم يتم العثور على الصفحة المطلوبة ('+id+') ! </p></div><div class="modal-footer"><a href="#" class="btn btn-primary" data-dismiss="modal">إغلاق</a></div></div>').modal();
			
			//id = cfg.StartPage;
			Forkan.navigate("", true);
			return false;
		}
		
		self.requestedId = id;
		//if (!this.Ayas){this.init();}
              
		//$("#ipage").animate({right: '-500px'},"700",function() { $(this).hide().empty() });
		$("#ipage").animate({"width": "toggle", "opacity": "toggle"}, 300,function() { $(this).hide().empty() });

		//--- get Ayas
        this.Ayas.fetch({
				url : cfg.url()+"/ayas/page/"+id,
                success: function() {
				
				
				$('#activePage').html(id);
				
				$("#ipage").animate({"width": "toggle", "opacity": "toggle"}, 600,function() { $(this).show() });	
				//$("#ipage").animate({right: '0px'},"700",function() { $(this).show() });
		    	
				if(!self.AyasView) { self.AyasView = new viewAyas({model: self.Ayas});
				self.AyasView.render();
				}
				
				//--- get Tafseer
				self.Tafseers.fetch({
						url : cfg.url()+"/tafseer/"+cfg.tafseer+"/page/"+id,
						success: function() {
						// focus in first aya
						$('.iAya').first().click(); }
				});
            }
        });  
		

    } ,
	
    gotoAyaInPage: function(id,yid) {
		var self = this;

		self.requestedId = id;		
		//if (!this.Ayas){this.init();}
              
		$("#ipage").animate({right: '-500px'},"700",function() { $(this).hide().empty() });


         this.Ayas.fetch({
				url : cfg.url()+"/ayas/page/"+id,
                success: function() {
				//$("#ipage").slideDown();				
				$("#ipage").animate({right: '0px'},"700",function() { $(this).show();
				$("#ya"+yid).click(); });
				// focus in aya
		    	/* ; //self.AyasView = new viewAyas({model: self.Ayas});
				//self.AyasView.render();
                    //if (self.requestedId) self.wineDetails(self.requestedId);*/
            }
        });

    } ,

    getPages: function() {
		var self = this;
		
		//if (!this.Ayas){this.init();}
              
         this.Pages.fetch({
				url : cfg.hh.url()+"/aya/"+id+"/to/"+nbr/* ; 
                success: function() {
				$("#ipage").empty();
		    	//self.AyasView = new viewAyas({model: self.Ayas});
				//self.AyasView.render();
                    //if (self.requestedId) self.wineDetails(self.requestedId);
            }*/
        });
		
    }
 
});
 
  
var Forkan = new AppRouter();

	Forkan.init();
    Forkan.view = new AppView;

	
	//var header = new HeaderView();
  // Finally, we kick things off by creating the **App**.

});
