(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){

},{}],2:[function(require,module,exports){
(function (global){
var $ = (typeof window !== "undefined" ? window['jQuery'] : typeof global !== "undefined" ? global['jQuery'] : null);
var typeahead = require('typeahead.js');

var addSelected = function($el, $selections, sel, display, template){
  $selections.find('.empty-selection').hide();
  
  var text;
  if (template && typeof template === 'function') {
    text = template(sel);
  } else {
    text = display ? sel[display] : sel;
  }
  var itemid = 'item_' + text;
  
  if (!$selections.find('#'+itemid).length) {
    $('<li id="'+itemid+'" class="ttmulti-selection list-group-item">' + text + '<i class="js-remove pull-right" style="cursor:pointer">✖</i></li>')
      .data("__ttmulti_data__", sel)
      .data("val", text)
      .appendTo( $selections )
      .find(".js-remove").bind("click", function(){
        this.parentNode.remove();
        if ($selections.find('li:not(.empty-selection)').length === 0) {
          $selections.find('.empty-selection').show();
        }
        $el.trigger('selectionRemoved', sel);
      });
    
    $el.trigger('selectionAdded', sel);
  }
};
var addEmpty = function ($selections, empty_selection) {
  var text;
  if (!empty_selection) {
    text = 'No selections.';
  } else {
    if (typeof empty_selection === 'function') {
      text = empty_selection();
    } else {
      text = '' + empty_selection;
    }
  }
  
  $('<li class="empty-selection list-group-item">' + text + '</li>')
    .appendTo( $selections );
};


$.fn.typeaheadmulti = function(options, dataset){
  function initialize(options, dataset){ //TODO: accept multiple datasets.
    var display = dataset ? dataset.display : undefined;
    var templates = dataset.templates || {};
    
    this.each(function(){
      var $el = $(this);
      var selections_id = Math.random().toString(36).slice(2);
      $el.data('selectionsContainer', selections_id);
      
      var $selections = $('<ul>').addClass("ttmulti-selections list-group").css('margin-bottom', 0);
      if (options.selectionsContainer) {
        $(options.selectionsContainer).append($selections);
      } else{
        $selections.insertAfter($el);
      }
      
      $selections.attr('id', selections_id);
      // addEmpty($selections, templates.emptySelection);
      
      $el.typeahead(options, dataset)
        .bind('typeahead:select', function(ev, selection) {
          $('.no-selected-user').hide();
          addSelected($el, $selections, selection, display, templates.selection);
          $el.typeahead('val', '');
        });  
    });
  
  }

  function getVal(newVal){
    // TODO: Check if the val is set. typeaheadmulti('val', someval)
    $input = this.filter('.tt-input');
    return(
      $('#' + $input.data('selectionsContainer'))
        .find(".ttmulti-selection").map(function(){
          return $(this).data("__ttmulti_data__");
        })
    );
  }

  if (options === 'val'){ 
    return getVal.call(this, [].slice.call(arguments, 1));
  }else{
    return initialize.call(this, options, dataset);
  }

  return this;
};


}).call(this,typeof global !== "undefined" ? global : typeof self !== "undefined" ? self : typeof window !== "undefined" ? window : {})
},{"typeahead.js":1}]},{},[2]);
