/**
 * Namespace for labels.
 */
WCF.Label = {};

/**
 * Provides enhancements for ACP label management.
 */
WCF.Label.ACPList = Class.extend({
	/**
	 * input element
	 * @var	jQuery
	 */
	_labelInput: null,
	
	/**
	 * list of pre-defined label items
	 * @var	array<jQuery>
	 */
	_labelList: [ ],
	
	/**
	 * Intitializes the ACP label list.
	 */
	init: function() {
		this._labelInput = $('#label').keydown($.proxy(this._keyPressed, this)).keyup($.proxy(this._keyPressed, this)).blur($.proxy(this._keyPressed, this));
		
		$('#labelList').find('input[type="radio"]').each($.proxy(function(index, input) {
			var $input = $(input);
			
			// ignore custom values
			if ($input.prop('value') !== 'custom') {
				this._labelList.push($($input.next('span')));
			}
		}, this));
	},
	
	/**
	 * Renders label name as label or falls back to a default value if label is empty.
	 */
	_keyPressed: function() {
		var $text = this._labelInput.prop('value');
		if ($text === '') $text = WCF.Language.get('wcf.acp.label.defaultValue');
		
		for (var $i = 0, $length = this._labelList.length; $i < $length; $i++) {
			this._labelList[$i].text($text);
		}
	}
});

/**
 * Provides simple logic to inherit associations within structured lists.
 */
WCF.Label.ACPList.Connect = Class.extend({
	/**
	 * Initializes inheritation for structured lists.
	 */
	init: function() {
		var $listItems = $('#connect .structuredList li');
		if (!$listItems.length) return;
		
		$listItems.each($.proxy(function(index, item) {
			$(item).find('input[type="checkbox"]').click($.proxy(this._click, this));
		}, this));
	},
	
	/**
	 * Marks items as checked if they're logically below current item.
	 * 
	 * @param	object		event
	 */
	_click: function(event) {
		var $listItem = $(event.currentTarget);
		if ($listItem.is(':checked')) {
			$listItem = $listItem.parents('li');
			var $depth = $listItem.data('depth');
			
			while (true) {
				$listItem = $listItem.next();
				if (!$listItem.length) {
					// no more siblings
					return true;
				}
				
				// element is on the same or higher level (= lower depth)
				if ($listItem.data('depth') <= $depth) {
					return true;
				}
				
				$listItem.find('input[type="checkbox"]').prop('checked', 'checked');
			}
		}
	}
});

/**
 * Provides a preview for label selections.
 * 
 * @param	string		elementSelector
 * @param	boolean		previewOnInit
 */
WCF.Label.Preview = Class.extend({
	/**
	 * list of selections
	 * @var	object
	 */
	_elements: { },
	
	/**
	 * list of preview elements
	 * @var	object
	 */
	_previews: { },
	
	/**
	 * Creates a new label preview for affected select elements.
	 * 
	 * @param	string		elementSelector
	 * @param	boolean		previewOnInit
	 */
	init: function(elementSelector, previewOnInit) {
		var $elements = $(elementSelector);
		if (!$elements.length) {
			return;
		}
		
		$elements.each($.proxy(function(index, element) {
			var $element = $(element);
			var $elementID = $element.wcfIdentify();
			
			this._elements[$elementID] = $element;
			this._previews[$elementID] = $('&nbsp;<span />').hide().insertAfter($element);
			
			$element.change($.proxy(this._change, this));
			
			if (previewOnInit) {
				this._showPreview($elementID);
			}
		}, this));
	},
	
	/**
	 * Updates preview on change.
	 * 
	 * @param	object		event
	 */
	_change: function(event) {
		var $elementID = $(event.currentTarget).wcfIdentify();
		this._showPreview($elementID);
	},
	
	/**
	 * Shows (or hides) preview for labels.
	 * 
	 * @param	string		elementID
	 */
	_showPreview: function(elementID) {
		var $element = this._elements[elementID];
		
		// get selected option
		var $selectedOption = $element.children('option:selected');
		
		this._previews[elementID].removeClass().empty().hide();
		if ($selectedOption.prop('value')) {
			this._previews[elementID].addClass('wcf-label').addClass($selectedOption.data('cssClassName')).html($selectedOption.text()).show();
		}
	}
});

WCF.Label.Chooser = Class.extend({
	_containers: { },
	
	init: function(selectedLabelIDs) {
		this._containers = { };
		
		// init containers
		this._initContainers();
		
		// pre-select labels
		if (selectedLabelIDs.length) {
			for (var $containerID in this._containers) {
				this._containers[$containerID].find('.dropdownMenu > li').each($.proxy(function(index, label) {
					var $label = $(label);
					var $labelID = $label.data('labelID') || 0;
					if ($labelID && WCF.inArray($labelID, selectedLabelIDs)) {
						this._selectLabel($label);
					}
				}, this));
			}
		}
		
		$('#postContainer').submit($.proxy(this._submit, this));
	},
	
	_initContainers: function() {
		$('.labelChooser').each($.proxy(function(index, container) {
			var $container = $(container);
			var $containerID = $container.wcfIdentify();
			
			if (!this._containers[$containerID]) {
				this._containers[$containerID] = $container;
				var $dropdownMenu = $container.find('.dropdownMenu');
				$dropdownMenu.find('li').click($.proxy(this._click, this));
				
				if (!$container.data('forceSelection')) {
					$('<li class="dropdownDivider" />').appendTo($dropdownMenu);
					
					var $buttonEmpty = $('<li><span><span class="badge label">' + WCF.Language.get('wcf.label.none') + '</span></span></li>').appendTo($dropdownMenu);
					$buttonEmpty.click($.proxy(this._click, this));
				}
			}
		}, this));
	},
	
	_click: function(event) {
		this._selectLabel($(event.currentTarget));
	},
	
	_selectLabel: function(label) {
		var $container = label.parents('.dropdown');
		
		// save label id
		if (label.data('labelID')) {
			$container.data('labelID', label.data('labelID'));
		}
		else {
			$container.data('labelID', 0);
		}
		
		// replace button
		label = label.find('span > span');
		$container.find('.dropdownToggle > span').removeClass().addClass(label.attr('class')).text(label.text());
	},
	
	_submit: function() {
		// get form submit area
		var $formSubmit = $('#postContainer').find('.formSubmit');
		
		// remove old, hidden values
		$formSubmit.find('input[type="hidden"]').each(function(index, input) {
			var $input = $(input);
			if ($input.attr('name') === 'labelIDs[]') {
				$input.remove();
			}
		});
		
		// insert label ids
		for (var $containerID in this._containers) {
			var $container = this._containers[$containerID];
			if ($container.data('labelID')) {
				$('<input type="hidden" name="labelIDs[]" value="' + $container.data('labelID') + '" />').appendTo($formSubmit);
			}
		}
	}
});