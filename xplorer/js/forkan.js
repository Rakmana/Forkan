
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
			ApiServer  : "http://localhost/forkan/api/",
			version    : "2.0",
			key        : "28e336ac6c9423d946ba02d19c6a2632",
			riwaya     : "1", // riwaya  [1:hafs 2:warch ...]
			tafseer    : "1", // Tafseer [1:tabari 2:jalalyn ...] 
			telawa     : "1", // Telawa  [1:Sudais 2:Afasy ...] 
			StartAya   : "1",
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

    // Default attributes for the todo.
    defaults: {
      yid   : 0,
      sur   : 0,
      aya   : 0,
      txt   : '',
      rwy   : 0/*,
	  audio  : '',
	  views  : 0*/
    },

    // Ensure that each todo created has `content`.
    initialize: function() {
      //if (!this.get("index")) {
		//this.destroy();
      //};
	  //--- make attr more readable then "array[x]
	  this.set({yid : this.get("yid"),
	            sur  : this.get("sur"),
	            aya   : this.get("aya"),
	            txt  : this.get("txt")
	            //"riwaya": this.get("rw")
				});
				
		//alert(JSON.stringify(this));
    },
    // Remove this Todo from *localStorage* and delete its view.
    clear: function() {
      this.destroy();
    }

  });

// Ayas Collection
  var colAyas = Backbone.Collection.extend({

    // Reference to this collection's model.
    model: modAya,
	  
	url: function(){
        //var arg={cls:"q",act:"get",ayaID:1,nbr:17};
		return "aya/";
	},

	parse: function(response) {
       return response.dt;
    },
    // Save all of the todo items under the `"todos"` namespace.
    //localStorage: new Store("Ayas"),

    // Filter down the list of all todo items that are finished.
    /*done: function() {
      return this.filter(function(todo){ return todo.get('done'); });
    },*/

    // Filter down the list to only todo items that are still not finished.
    /*remaining: function() {
      return ;//this.without.apply(this, this.done());
    },*/

    // We keep the Ayas in sequential order, despite being saved by unordered
    // GUID in the database. This generates the next order number for new items.
    nextOrder: function() {
      if (!this.length) return 1;
      return this.last().get('order') + 1;
    },

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
      "click .iAya"                 : "iFocus",
      //"focusout .iAya"              : "iBlur",
      //"dblclick div.todo-content" : "edit",
      //"click span.todo-destroy"   : "clear",
      //"keypress .todo-input"      : "updateOnEnter",
    },
	
    initialize: function() {
      _.bindAll(this, 'render', 'close'/*, 'remove'*/);
      this.model.bind('change', this.render);
      this.model.bind('destroy', this.remove);

    },

    // Re-render the contents of the todo item.
    render: function() {
      $(this.el).html(this.template(this.model.toJSON()));
	  //this.ya = this.$(".iAya");
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
    },

    // Close the `"editing"` mode, saving changes to the todo.
    /*close: function() {
      //this.model.save({content: this.input.val()});
      $(this.el).removeClass("editing");
    },*/

    // If you hit `enter`, we're through editing the item.
    /*updateOnEnter: function(e) {
      if (e.keyCode == 13) this.close();
    },*/
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
    },
    render: function(eventName) {
        $(this.el).empty();
		_.each(this.model.models, function(iModel) {
			
            //TODO: add cfg 4: append vs prepend
			$(this.el).prepend(
                new viewAya({model: iModel}).render().el);
        }, this);
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
 
    initialize: function() {
        this.model.bind("change", this.render, this);
        this.model.bind("destroy", this.close, this);
    },
 
    render: function(eventName) {
        $(this.el).html(this.template(this.model.toJSON()));
        return this;
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
        return cfg.url()+'page';
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
      sid  : 0,
      sta  : 0,
      ays   : 0,
      ord  : 0,
      rukus  : 0,
      nam   : '',
	  tnm  : '',
	  enm  : '',
	  typ   : '',
      audio  : ''
    },

    initialize: function() {
      //if (!this.get("name")) {
		//this.set({"name": this.defaults.name});
		
      //};
	  //--- make attr more readable then "array[x]
	  this.set({"sta" : this.get("sta"),
	            "sid"  : this.get("sid"),
	            "ays"  : this.get("ays"),
	            "ord" : this.get("ord"),
	            //"rukus" : this.get("rukus"),
	            "nam"  : this.get("nam"),
	            //"tname" : this.get("tname"),
	            "enm" : this.get("enm"),
	            "typ"  : this.get("typ")
	            //"audio" : this.get("audio")
	            //"riwaya": this.get("rw")
				});
				
		//alert(JSON.stringify(this));
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
		return '/#';//cfg.url()+"#sura";//+cfg.StartAya+"/nbr=15";
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
        Forkan.navigate("aya/"+(sura.get("sta"))+'/to/'+sura.get("ays"), true);
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
    },
    render: function(eventName) {
		$(this.el).empty();
        _.each(this.model.models, function(iModel) {
            //TODO: add cfg 4: append vs prepend
			$(this.el).prepend(
                new viewSura({model: iModel}).render().el);
        }, this);
        return this;
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
	  
            $("*[rel=twipsy]").twipsy({
               live: true
            });
			$("*[rel=popover]")
                .popover({
                  offset: 10
                })
                .click(function(e) {
                  e.preventDefault()
                });

			$('.dropdown').dropdown();
			
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
        ""                : "list",
        "aya/:id/to/:nbr" : "getAyas",
        "page"            : "getPages",
    },
	init: function(){
        var self = this;
		// initialize All object
        this.Ayas   = new colAyas();
		this.Ayas.fetch({
			url : cfg.ApiServer + cfg.version +'/'+ cfg.key+'/aya/1/to/7',
			success: function() {
		    	self.AyasView = new viewAyas({model: self.Ayas});
				self.AyasView.render();
				//if (self.requestedId) self.getAya(self.requestedId);
			}
		});
		
        this.Suras  = new colSuras();
		this.Suras.fetch({
			url : cfg.ApiServer + cfg.version +'/'+ cfg.key+'/sura',
			success: function() {
		    	self.SurasView = new viewSuras({model: self.Suras});
				self.SurasView.render();
				//if (self.requestedId) self.getAya(self.requestedId);
			}
		});
		
        this.Pages  = new colPages();
		this.Pages.fetch({
			url : cfg.ApiServer + cfg.version +'/'+ cfg.key+'/page',
			success: function() {
		    	self.PagesView = new viewPages({model: self.Pages});
				self.PagesView.render();
				//if (self.requestedId) self.getAya(self.requestedId);
			}
		});
	  
	  Forkan.Ayas.bind('add',     this.irender);
      Forkan.Ayas.bind('reset',   this.irender);
      Forkan.Ayas.bind('all',     this.irender);
      
      Forkan.Suras.bind('add',     this.irender);
      Forkan.Suras.bind('reset',   this.irender);
      Forkan.Suras.bind('all',     this.irender);
	  
	},    
	irender: function() {
	  
            $("*[rel=twipsy]").twipsy({
               live: true
            });
			$("*[rel=popover]")
                .popover({
                  offset: 10
                })
                .click(function(e) {
                  e.preventDefault()
                });

			$('.dropdown').dropdown();
			
	},
    list: function() {
        //$("#ipage").empty();
		/*var self = this;		
*/
    },
 
    getAya: function(id) {
        if (this.Ayas)
        {
            this.Aya = this.Ayas.get(id);
            if (this.AyaView) this.AyaView.close();
                    this.AyaView = new AyaView({model: this.Aya});
            this.AyaView.render();
        } else {
            this.requestedId = id;
            this.init();
        }    
    },
    
    getAyas: function(id,nbr) {
		var self = this;
		
		//if (!this.Ayas){this.init();}
              
		$("#ipage").slideUp("slow");

         this.Ayas.fetch({
				url : cfg.url()+"/aya/"+id+"/to/"+nbr,
                success: function() {
				$("#ipage").slideDown();
		    	/* ; //self.AyasView = new viewAyas({model: self.Ayas});
				//self.AyasView.render();
                    //if (self.requestedId) self.wineDetails(self.requestedId);*/
            }
        });
		/*
		if (this.Ayas)
		{
			this.wine = this.wineList.get(id);
			if (this.wineView) this.wineView.close();
		    		this.wineView = new WineView({model: this.wine});
			this.wineView.render();
		} else {
			this.requestedId = id;
			this.list();
		}
   */
    } ,   
    getPages: function() {
		var self = this;
		
		//if (!this.Ayas){this.init();}
              
         this.Pages.fetch({
				url : cfg.url()+"/aya/"+id+"/to/"+nbr/* ; 
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
	Backbone.history.start();
	
	//var header = new HeaderView();
  // Finally, we kick things off by creating the **App**.

});
