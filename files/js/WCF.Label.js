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
 * Provides a flexible label chooser.
 * 
 * @param	array<integer>	selectedLabelIDs
 * @param	string		containerSelector
 * @param	string		submitButtonSelector
 */
WCF.Label.Chooser = Class.extend({
	/**
	 * label container
	 * @var	jQuery
	 */
	_container: null,
	
	/**
	 * list of label groups
	 * @var	object
	 */
	_containers: { },
	
	/**
	 * Initializes a new label chooser.
	 * 
	 * @param	array<integer>	selectedLabelIDs
	 * @param	string		containerSelector
	 * @param	string		submitButtonSelector
	 */
	init: function(selectedLabelIDs, containerSelector, submitButtonSelector) {
		// init containers
		this._initContainers();
		
		// pre-select labels
		if (selectedLabelIDs.length) {
			for (var $containerID in this._containers) {
				this._containers[$containerID].find('.dropdownMenu > li').each($.proxy(function(index, label) {
					var $label = $(label);
					var $labelID = $label.data('labelID') || 0;
					if ($labelID && WCF.inArray($labelID, selectedLabelIDs)) {
						this._selectLabel($label, true);
					}
				}, this));
			}
		}
		
		// mark all containers as initialized
		for (var $containerID in this._containers) {
			var $dropdown = this._containers[$containerID];
			if ($dropdown.data('labelID') === undefined) {
				$dropdown.data('labelID', 0);
			}
		}
		
		if (containerSelector) {
			this._container = $(containerSelector);
			if (submitButtonSelector) {
				$(submitButtonSelector).click($.proxy(this._submit, this));
			}
			else {
				this._container.submit($.proxy(this._submit, this));
			}
		}
	},
	
	/**
	 * Initializes label groups.
	 */
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
	
	/**
	 * Handles label selections.
	 * 
	 * @param	object		event
	 */
	_click: function(event) {
		this._selectLabel($(event.currentTarget), false);
	},
	
	/**
	 * Selects a label.
	 * 
	 * @param	jQuery		label
	 * @param	boolean		onInit
	 */
	_selectLabel: function(label, onInit) {
		var $container = label.parents('.dropdown');
		
		// already initialized, ignore
		if (onInit && $container.data('labelID') !== undefined) {
			return;
		}
		
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
	
	/**
	 * Creates hidden input elements on submit.
	 */
	_submit: function() {
		// get form submit area
		var $formSubmit = this._container.find('.formSubmit');
		
		// remove old, hidden values
		$formSubmit.find('input[type="hidden"]').each(function(index, input) {
			var $input = $(input);
			if ($input.attr('name').indexOf('labelIDs[') === 0) {
				$input.remove();
			}
		});
		
		// insert label ids
		for (var $containerID in this._containers) {
			var $container = this._containers[$containerID];
			if ($container.data('labelID')) {
				$('<input type="hidden" name="labelIDs[' + $container.data('groupID') + ']" value="' + $container.data('labelID') + '" />').appendTo($formSubmit);
			}
		}
	}
});