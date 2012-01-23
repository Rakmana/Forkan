(function($){
  
  var defaults = {
    rmz        : "28e336ac6c9423d946ba02d19c6a2632",
    riwaya     : "1", // riwaya  [1:hafs 2:warch ...]
    tafseer    : "1", // Tafseer [1:tabari 2:jalalyn ...] 
    telawa     : "1", // Telawa  [1:Sudais 2:Afasy ...] 
    StartAya   : "1"
  };
  
  var App = {
    ApiServer  : "http://localhost/forkan/api/",
    version    : "1.0",
    rmz        : "28e336ac6c9423d946ba02d19c6a2632"
  };  
  $.forkan = function( callback, params ) {
    //if ( !url || !callback ) throw("url and callback required");
    
    var args = { rmz: App.rmz,
                 ver: App.version,
                 byn: params };
    //params.longUrl = url;
    
    return $.getJSON(App.ApiServer, (args), function(data, status, xhr){
      callback(data);
    });
  };

})(jQuery);


  $.forkan(function(data){
             //alert(JSON.stringify(data.dt));
             var test = new Backbone.Collection(data.dt);
           },'{"cls":"Forkan","act":"read","ayaID":1}');

// Load the application once the DOM is ready, using `jQuery.ready`:
$(function(){

  // Forkan Model
  // ----------

  // Our basic **Aya** model has [index, sura, aya, text, riwaya, audio  and views] attributes.
  var Aya = Backbone.Model.extend({

    // Default attributes for the todo.
    defaults: {
      index  : 0,
      sura   : 0,
      aya    : 0,
      text   : '',
      riwaya : 0,
	  audio  : '',
	  views  : 0
    },

    // Ensure that each todo created has `content`.
    initialize: function() {
      if (!this.get("text")) {
        // TODO: do ajax call to get from api server
		this.set({"text": this.defaults.content});
      }
    },

    // Toggle the `done` state of this todo item.
    toggle: function() {
      this.save({done: !this.get("done")});
    },

    // Remove this Todo from *localStorage* and delete its view.
    clear: function() {
      this.destroy();
    }

  });

  // Todo Collection
  // ---------------

  // The collection of aya is backed by *localStorage* instead of a remote
  // server.
  var Ayas = Backbone.Collection.extend({

    // Reference to this collection's model.
    model: Aya,

    // Save all of the todo items under the `"todos"` namespace.
    localStorage: new Store("todos-backbone"),

    // Filter down the list of all todo items that are finished.
    done: function() {
      return this.filter(function(todo){ return todo.get('done'); });
    },

    // Filter down the list to only todo items that are still not finished.
    remaining: function() {
      return this.without.apply(this, this.done());
    },

    // We keep the Todos in sequential order, despite being saved by unordered
    // GUID in the database. This generates the next order number for new items.
    nextOrder: function() {
      if (!this.length) return 1;
      return this.last().get('order') + 1;
    },

    // Todos are sorted by their original insertion order.
    comparator: function(todo) {
      return todo.get('order');
    }

  });

  // Create our global collection of **Todos**.
  var Ayas = new Ayas;

  // Todo Item View
  // --------------

  // The DOM element for a todo item...
  var AyaView = Backbone.View.extend({

    //... is a list tag.
    tagName:  "span",

    // Cache the template function for a single item.
    template: _.template($('#item-template').html()),

    // The DOM events specific to an item.
    events: {
      "click .check"              : "toggleDone",
      "dblclick div.todo-content" : "edit",
      "click span.todo-destroy"   : "clear",
      "keypress .todo-input"      : "updateOnEnter",
      "blur .todo-input"          : "close"
    },

    // The TodoView listens for changes to its model, re-rendering. Since there's
    // a one-to-one correspondence between a **Todo** and a **TodoView** in this
    // app, we set a direct reference on the model for convenience.
    initialize: function() {
      _.bindAll(this, 'render', 'close', 'remove');
      this.model.bind('change', this.render);
      this.model.bind('destroy', this.remove);
    },

    // Re-render the contents of the todo item.
    render: function() {
      $(this.el).html(this.template(this.model.toJSON()));
      this.input = this.$('.todo-input');
      return this;
    },

    // Toggle the `"done"` state of the model.
    toggleDone: function() {
      this.model.toggle();
    },

    // Switch this view into `"editing"` mode, displaying the input field.
    edit: function() {
      $(this.el).addClass("editing");
      this.input.focus();
    },

    // Close the `"editing"` mode, saving changes to the todo.
    close: function() {
      this.model.save({content: this.input.val()});
      $(this.el).removeClass("editing");
    },

    // If you hit `enter`, we're through editing the item.
    updateOnEnter: function(e) {
      if (e.keyCode == 13) this.close();
    },

    // Remove the item, destroy the model.
    clear: function() {
      this.model.clear();
    }

  });

  // The Application
  // ---------------

  // Our overall **AppView** is the top-level piece of UI.
  var AppView = Backbone.View.extend({

    // Instead of generating a new element, bind to the existing skeleton of
    // the App already present in the HTML.
    el: $("#todoapp"),

    // Our template for the line of statistics at the bottom of the app.
    statsTemplate: _.template($('#stats-template').html()),

    // Delegated events for creating new items, and clearing completed ones.
    events: {
      "keypress #new-todo":  "createOnEnter",
      "keyup #new-todo":     "showTooltip",
      "click .todo-clear a": "clearCompleted",
      "click .mark-all-done": "toggleAllComplete"
    },

    // At initialization we bind to the relevant events on the `Todos`
    // collection, when items are added or changed. Kick things off by
    // loading any preexisting todos that might be saved in *localStorage*.
    initialize: function() {
      _.bindAll(this, 'addOne', 'addAll', 'render', 'toggleAllComplete');

      this.input = this.$("#new-todo");
      this.allCheckbox = this.$(".mark-all-done")[0];

      Ayas.bind('add',     this.addOne);
      Ayas.bind('reset',   this.addAll);
      Ayas.bind('all',     this.render);

      Ayas.fetch();
      
    },

    // Re-rendering the App just means refreshing the statistics -- the rest
    // of the app doesn't change.
    render: function() {
      var done = Ayas.done().length;
      var remaining = Ayas.remaining().length;

      this.$('#todo-stats').html(this.statsTemplate({
        total:      Ayas.length,
        done:       done,
        remaining:  remaining
      }));

      this.allCheckbox.checked = !remaining;
    },

    // Add a single todo item to the list by creating a view for it, and
    // appending its element to the `<ul>`.
    addOne: function(todo) {
      var view = new AyaView({model: todo});
      this.$("#todo-list").append(view.render().el);
    },

    // Add all items in the **Todos** collection at once.
    addAll: function() {
      Ayas.each(this.addOne);
    },

    // Generate the attributes for a new Todo item.
    newAttributes: function() {
      return {
        content: this.input.val(),
        order:   Ayas.nextOrder(),
        done:    false
      };
    },

    // If you hit return in the main input field, create new **Todo** model,
    // persisting it to *localStorage*.
    createOnEnter: function(e) {
      if (e.keyCode != 13) return;
      Ayas.create(this.newAttributes());
      this.input.val('');
    },

    // Clear all done todo items, destroying their models.
    clearCompleted: function() {
      _.each(Ayas.done(), function(aya){ aya.clear(); });
      return false;
    },

    // Lazily show the tooltip that tells you to press `enter` to save
    // a new todo item, after one second.
    showTooltip: function(e) {
      var tooltip = this.$(".ui-tooltip-top");
      var val = this.input.val();
      tooltip.fadeOut();
      if (this.tooltipTimeout) clearTimeout(this.tooltipTimeout);
      if (val == '' || val == this.input.attr('placeholder')) return;
      var show = function(){ tooltip.show().fadeIn(); };
      this.tooltipTimeout = _.delay(show, 1000);
    },

    toggleAllComplete: function () {
      var done = this.allCheckbox.checked;
      Ayas.each(function (aya) { aya.save({'done': done}); });
    }

  });

  // Finally, we kick things off by creating the **App**.
  var App = new AppView;

});
