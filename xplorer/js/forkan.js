
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
		$("#iside").html(this.SideTemplate(this.model.toJSON()));
		
		var aya = this;
		
		// change currents breadcrumbe
		Forkan.Suras.get(aya.model.get('sur')).active();
		
		/*var ss = Forkan.Suras.models;
		var ps = Forkan.Pages.models;
		//alert(JSON.stringify(ss[5].get('sta')));
		var p = Forkan.Pages.find(function(page){
			if(page.get('pid')){
			var p1 = ps[page.get('pid')]; //console.log('p1='+p1.get('pid'));
			var p2 = ps[(parseInt(page.get('pid'))+1)]; //console.log('p2='+p1.get('pid'));
			
			var sur = ss[page.get('sur')];//console.log('s1='+sur.get('sid'));
			var sur2= ss[p2.get('sur')];  //console.log('s2='+sur2.get('sid'));
			
			var start = parseInt(sur.get('sta'))+parseInt(page.get('aya'))-1;
			var end = sur2.get('sta') ;
			//var end   = start + page.get('pid')+1].get('ays');
			console.log('s:'+start+' e:'+end+' y:'+aya.model.get('yid'));
			//console.log('p1='+p1.get('pid')+' p2='+p2.get('pid')+' s1='+sur.get('sid')+' s2='+sur2.get('sid'));
			
			return (aya.model.get('yid') >= start) && (aya.model.get('yid') < end);
			}
		});
		//alert(JSON.stringify(p));
		p.active();
	  */
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
//var Ayas = new colAyas;
//var Suras = new colSuras;
//var Ayas = new colAyas;
  // Our overall **AppView** is the top-level piece of UI.
  var AppView = Backbone.View.extend({

    // Instead of generating a new element, bind to the existing skeleton of
    // the App already present in the HTML.
    el: $("#forkanApp"),

    // Our template for the line of statistics at the bottom of the app.
    //statsTemplate: _.template($('#stats-template').html()),

    // Delegated events for creating new items, and clearing completed ones.
    events: {
      //"keypress #new-todo":  "createOnEnter",
      //"keyup #new-todo":     "showTooltip",
      //"click .iSura": "GotoSura",
      //"click .mark-all-done": "toggleAllComplete"
    },

    initialize: function() {
      //_.bindAll(this, 'addAya', 'addAyas', 'addSura', 'addSuras', 'render');/, 'toggleAllComplete'

      //this.input = this.$("#new-todo");
      //this.allCheckbox = this.$(".mark-all-done")[0];

      /*Forkan.Ayas.bind('add',     this.render);
      Forkan.Ayas.bind('reset',   this.render);
      Forkan.Ayas.bind('all',     this.render);
      
      Forkan.Suras.bind('add',     this.render);
      Forkan.Suras.bind('reset',   this.render);
      Forkan.Suras.bind('all',     this.render);*/

      
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
			
	},
    // Add a single todo item to the list by creating a view for it, and
    // appending its element to the `<ul>`.
    addAya: function(aya) {
      var view = new viewAya({model: aya});
      this.$("#ipage").prepend(view.render().el);
    },

    // Add all items in the **Todos** collection at once.
    addAyas: function() {
      Ayas.each(this.addAya);
    },
    // Add a single todo item to the list by creating a view for it, and
    // appending its element to the `<ul>`.
    addSura: function(sura) {
      var view = new viewSura({model: sura});
      $("#suraList").append(view.render().el);
      
    },

    // Add all items in the **Todos** collection at once.
    addSuras: function() {
      Suras.each(this.addSura);
    },

    // Generate the attributes for a new Todo item.
    newAttributes: function() {

    }



    // Lazily show the tooltip that tells you to press `enter` to save
    // a new todo item, after one second.
    /*showTooltip: function(e) {
      var tooltip = this.$(".ui-tooltip-top");
      var val = this.input.val();
      tooltip.fadeOut();
      if (this.tooltipTimeout) clearTimeout(this.tooltipTimeout);
      if (val == '' || val == this.input.attr('placeholder')) return;
      var show = function(){ tooltip.show().fadeIn(); };
      this.tooltipTimeout = _.delay(show, 1000);
    },*/


  });



 
var AppRouter = Backbone.Router.extend({
 
    routes: {
        //"ayas/:id/to/:nbr"  : "getAyas",
        "ayas/page/:id"     : "getAyasPerPage",
        //"ayas/page/:id/:yid": "gotoAyaInPage",
        "page"              : "getPages",
        ""                  : "home",
    },
	init: function(){
        var self = this;
		// initialize All object
		
        this.Ayas   = new colAyas();
        this.Suras  = new colSuras();
		this.Suras.fetch({
			url : cfg.ApiServer + cfg.version +'/'+ cfg.key+'/suras',
			success: function() {
		    	self.SurasView = new viewSuras({model: self.Suras});
				self.SurasView.render();
				//if (self.requestedId) self.getAya(self.requestedId);
		/*self.Ayas.fetch({
			url : cfg.url()+"/ayas/page/"+cfg.StartPage,
			success: function() {
		    	self.AyasView = new viewAyas({model: self.Ayas});
				self.AyasView.render();
				// focus in first aya
				$('.iAya').first().click();
				//if (self.requestedId) self.getAya(self.requestedId);
			}
		});*/
		
	Backbone.history.start();
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
				//if (self.requestedId) self.getAya(self.requestedId);
				$('#pg'+cfg.startPage).click();
				
			}
		});
	  
	  
	},    

	irender: function() {
	  
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
			
			
	},
 
    home: function() {
        var self = this;
		if (!self.requestedId){
		self.Ayas.fetch({
			url : cfg.url()+"/ayas/page/"+cfg.StartPage,
			success: function() {
		    	self.AyasView = new viewAyas({model: self.Ayas});
				self.AyasView.render();
				// focus in first aya
				$('.iAya').first().click();
				//if (self.requestedId) self.getAya(self.requestedId);
			}
		});}   
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
		
		self.requestedId = id;
		//if (!this.Ayas){this.init();}
              
		$("#ipage").animate({right: '-500px'},"700",function() { $(this).hide().empty() });


         this.Ayas.fetch({
				url : cfg.url()+"/ayas/page/"+id,
                success: function() {
				//$("#ipage").slideDown();				
				$("#ipage").animate({right: '0px'},"700",function() { $(this).show() });
		    	
				if(!self.AyasView) { self.AyasView = new viewAyas({model: self.Ayas});
				
				self.AyasView.render();}
				// focus in first aya
				$('.iAya').first().click();
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
