/* Widget: columnSelector (responsive table widget) - updated 1/10/2016 (v2.25.1) *//*
 * Requires tablesorter v2.8+ and jQuery 1.7+
 * by Justin Hallett & Rob Garrison
 */
/*jshint browser:true, jquery:true, unused:false */
/*global jQuery: false */
;(function($){
	'use strict';

	var ts = $.tablesorter,
	namespace = '.tscolsel',
	tsColSel = ts.columnSelector = {

		queryAll   : '@media only all { [columns] { display: none; } } ',
		queryBreak : '@media all and (min-width: [size]) { [columns] { display: table-cell; } } ',

		init: function(table, c, wo) {
			var $t, colSel;

			// abort if no input is contained within the layout
			$t = $(wo.columnSelector_layout);
			if (!$t.find('input').add( $t.filter('input') ).length) {
				if (c.debug) {
					console.error('ColumnSelector: >> ERROR: Column Selector aborting, no input found in the layout! ***');
				}
				return;
			}

			// unique table class name
			c.$table.addClass( c.namespace.slice(1) + 'columnselector' );

			// build column selector/state array
			colSel = c.selector = { $container : $(wo.columnSelector_container || '<div>') };
			colSel.$style = $('<style></style>').prop('disabled', true).appendTo('head');
			colSel.$breakpoints = $('<style></style>').prop('disabled', true).appendTo('head');

			colSel.isInitializing = true;
			tsColSel.setUpColspan(c, wo);
			tsColSel.setupSelector(c, wo);

			if (wo.columnSelector_mediaquery) {
				tsColSel.setupBreakpoints(c, wo);
			}

			colSel.isInitializing = false;
			if (colSel.$container.length) {
				tsColSel.updateCols(c, wo);
			} else if (c.debug) {
				console.warn('ColumnSelector: >> container not found');
			}

			c.$table
				.off('refreshColumnSelector' + namespace)
				/* $('table').trigger('refreshColumnSelector', arguments ); showing arguments below
					undefined = refresh current settings (update css hiding columns)
					'selectors' = update container contents (replace inputs/labels)
					[ [2,3,4] ] = set visible columns; turn off "auto" mode.
					[ 'columns', [2,3,4] ] = set visible columns; turn off "auto" mode.
					[ 'auto', [2,3,4] ] = set visible columns; turn on "auto" mode.
					true = turn on "auto" mode.
				*/
				.on('refreshColumnSelector' + namespace, function( e, optName, optState ){
					// make sure we're using current config settings
					tsColSel.refreshColumns( this.config, optName, optState );
				});

		},

		refreshColumns: function( c, optName, optState ) {
			var i, arry,
				isArry = $.isArray(optState || optName),
				wo = c.widgetOptions;
			// see #798
			if (typeof optName !== 'undefined' && c.selector.$container.length) {
				// pass "selectors" to update the all of the container contents
				if ( optName === 'selectors' ) {
					c.selector.$container.empty();
					tsColSel.setupSelector(c, wo);
					tsColSel.setupBreakpoints(c, wo);
					// if optState is undefined, maintain the current "auto" state
					if ( typeof optState === 'undefined' ) {
						optState = c.selector.auto;
					}
				}
				// pass an array of column zero-based indexes to turn off auto mode & toggle selected columns
				if (isArry) {
					arry = optState || optName;
					// make sure array contains numbers
					$.each(arry, function(i, v){
						arry[i] = parseInt(v, 10);
					});
					for (i = 0; i < c.columns; i++) {
						c.selector.$container
							.find('input[data-column=' + i + ']')
							.prop('checked', $.inArray( i, arry ) >= 0 );
					}
				}
				// if passing an array, set auto to false to allow manual column selection & update columns
				// refreshColumns( c, 'auto', true ) === refreshColumns( c, true );
				tsColSel
					.updateAuto( c, wo, c.selector.$container.find('input[data-column="auto"]')
					.prop('checked', optState === true || optName === true || optName === 'auto' && optState !== false) );
			} else {
				tsColSel.updateBreakpoints(c, wo);
				tsColSel.updateCols(c, wo);
			}
			tsColSel.adjustColspans( c, wo );
		},

		setupSelector: function(c, wo) {
			var index, name, $header, priority, col, colId,
				colSel = c.selector,
				$container = colSel.$container,
				useStorage = wo.columnSelector_saveColumns && ts.storage,
				// get stored column states
				saved = useStorage ? ts.storage( c.table, 'tablesorter-columnSelector' ) : [],
				state = useStorage ? ts.storage( c.table, 'tablesorter-columnSelector-auto') : {};

			// initial states
			colSel.auto = $.isEmptyObject(state) || $.type(state.auto) !== 'boolean' ? wo.columnSelector_mediaqueryState : state.auto;
			colSel.states = [];
			colSel.$column = [];
			colSel.$wrapper = [];
			colSel.$checkbox = [];
			// populate the selector container
			for ( index = 0; index < c.columns; index++ ) {
				$header = c.$headerIndexed[ index ];
				// if no data-priority is assigned, default to 1, but don't remove it from the selector list
				priority = $header.attr(wo.columnSelector_priority) || 1;
				colId = $header.attr('data-column');
				col = ts.getColumnData( c.table, c.headers, colId );
				state = ts.getData( $header, col, 'columnSelector');

				// if this column not hidable at all
				// include getData check (includes 'columnSelector-false' class, data attribute, etc)
				if ( isNaN(priority) && priority.length > 0 || state === 'disable' ||
					( wo.columnSelector_columns[colId] && wo.columnSelector_columns[colId] === 'disable') ) {
					continue; // goto next
				}

				// set default state; storage takes priority
				colSel.states[colId] = saved && typeof saved[colId] !== 'undefined' ?
					saved[colId] : typeof wo.columnSelector_columns[colId] !== 'undefined' ?
					wo.columnSelector_columns[colId] : (state === 'true' || state !== 'false');
				colSel.$column[colId] = $(this);

				// set default col title
				name = $header.attr(wo.columnSelector_name) || $header.text();
				if ($container.length) {
					colSel.$wrapper[colId] = $(wo.columnSelector_layout.replace(/\{name\}/g, name)).appendTo($container);
					colSel.$checkbox[colId] = colSel.$wrapper[colId]
						// input may not be wrapped within the layout template
						.find('input').add( colSel.$wrapper[colId].filter('input') )
						.attr('data-column', colId)
						.toggleClass( wo.columnSelector_cssChecked, colSel.states[colId] )
						.prop('checked', colSel.states[colId])
						.on('change', function(){
							// ensure states is accurate
							var colId = $(this).attr('data-column');
							c.selector.states[colId] = this.checked;
							tsColSel.updateCols(c, wo);
						}).change();
				}
			}

		},

		setupBreakpoints: function(c, wo) {
			var colSel = c.selector;

			// add responsive breakpoints
			if (wo.columnSelector_mediaquery) {
				// used by window resize function
				colSel.lastIndex = -1;
				tsColSel.updateBreakpoints(c, wo);
				c.$table
					.off('updateAll' + namespace)
					.on('updateAll' + namespace, function(){
						tsColSel.updateBreakpoints(c, wo);
						tsColSel.updateCols(c, wo);
					});
			}

			if (colSel.$container.length) {
				// Add media queries toggle
				if (wo.columnSelector_mediaquery) {
					colSel.$auto = $( wo.columnSelector_layout.replace(/\{name\}/g, wo.columnSelector_mediaqueryName) ).prependTo(colSel.$container);
					colSel.$auto
						// needed in case the input in the layout is not wrapped
						.find('input').add( colSel.$auto.filter('input') )
						.attr('data-column', 'auto')
						.prop('checked', colSel.auto)
						.toggleClass( wo.columnSelector_cssChecked, colSel.auto )
						.on('change', function(){
							tsColSel.updateAuto(c, wo, $(this));
						}).change();
				}
				// Add a bind on update to re-run col setup
				c.$table.off('update' + namespace).on('update' + namespace, function() {
					tsColSel.updateCols(c, wo);
				});
			}
		},

		updateAuto: function(c, wo, $el) {
			var colSel = c.selector;
			colSel.auto = $el.prop('checked') || false;
			$.each( colSel.$checkbox, function(i, $cb){
				if ($cb) {
					$cb[0].disabled = colSel.auto;
					colSel.$wrapper[i].toggleClass('disabled', colSel.auto);
				}
			});
			if (wo.columnSelector_mediaquery) {
				tsColSel.updateBreakpoints(c, wo);
			}
			tsColSel.updateCols(c, wo);
			// copy the column selector to a popup/tooltip
			if (c.selector.$popup) {
				c.selector.$popup.find('.tablesorter-column-selector')
					.html( colSel.$container.html() )
					.find('input').each(function(){
						var indx = $(this).attr('data-column');
						$(this).prop( 'checked', indx === 'auto' ? colSel.auto : colSel.states[indx] );
					});
			}
			if (wo.columnSelector_saveColumns && ts.storage) {
				ts.storage( c.$table[0], 'tablesorter-columnSelector-auto', { auto : colSel.auto } );
			}
			tsColSel.adjustColspans( c, wo );
			// trigger columnUpdate if auto is true (it gets skipped in updateCols()
			if (colSel.auto) {
				c.$table.triggerHandler(wo.columnSelector_updated);
			}
		},
		addSelectors: function( prefix, column ) {
			var array = [],
				temp = ' col:nth-child(' + column + ')';
			array.push(prefix + temp + ',' + prefix + '_extra_table' + temp);
			temp = ' tr:not(.hasSpan) th:nth-child(' + column + ')';
			array.push(prefix + temp + ',' + prefix + '_extra_table' + temp);
			temp = ' tr:not(.hasSpan) td:nth-child(' + column + ')';
			array.push(prefix + temp + ',' + prefix + '_extra_table' + temp);
			// for other cells in colspan columns
			temp = ' tr td:not(' + prefix + 'HasSpan)[data-column="' + (column - 1) + '"]';
			array.push(prefix + temp + ',' + prefix + '_extra_table' + temp);
			return array;
		},
		updateBreakpoints: function(c, wo) {
			var priority, col, column, breaks,
				isHidden = [],
				colSel = c.selector,
				prefix = c.namespace + 'columnselector',
				mediaAll = [],
				breakpts = '';
			if (wo.columnSelector_mediaquery && !colSel.auto) {
				colSel.$breakpoints.prop('disabled', true);
				colSel.$style.prop('disabled', false);
				return;
			}
			if (wo.columnSelector_mediaqueryHidden) {
				// add columns to be hidden; even when "auto" is set - see #964
				for ( column = 0; column < c.columns; column++ ) {
					col = ts.getColumnData( c.table, c.headers, column );
					isHidden[ column + 1 ] = ts.getData( c.$headerIndexed[ column ], col, 'columnSelector' ) === 'false';
					if ( isHidden[ column + 1 ] ) {
						// hide columnSelector false column (in auto mode)
						mediaAll = mediaAll.concat( tsColSel.addSelectors( prefix, column + 1 ) );
					}
				}
			}
			// only 6 breakpoints (same as jQuery Mobile)
			for (priority = 0; priority < 6; priority++){
				/*jshint loopfunc:true */
				breaks = [];
				c.$headers.filter('[' + wo.columnSelector_priority + '=' + (priority + 1) + ']').each(function(){
					column = parseInt($(this).attr('data-column'), 10) + 1;
					// don't reveal columnSelector false columns
					if ( !isHidden[ column ] ) {
						breaks = breaks.concat( tsColSel.addSelectors( prefix, column ) );
					}
				});
				if (breaks.length) {
					mediaAll = mediaAll.concat( breaks );
					breakpts += tsColSel.queryBreak
						.replace(/\[size\]/g, wo.columnSelector_breakpoints[priority])
						.replace(/\[columns\]/g, breaks.join(','));
				}
			}
			if (colSel.$style) {
				colSel.$style.prop('disabled', true);
			}
			if (mediaAll.length) {
				colSel.$breakpoints
					.prop('disabled', false)
					.text( tsColSel.queryAll.replace(/\[columns\]/g, mediaAll.join(',')) + breakpts );
			}
		},
		updateCols: function(c, wo) {
			if (wo.columnSelector_mediaquery && c.selector.auto || c.selector.isInitializing) {
				return;
			}
			var column,
				colSel = c.selector,
				styles = [],
				prefix = c.namespace + 'columnselector';
			colSel.$container.find('input[data-column]').filter('[data-column!="auto"]').each(function(){
				if (!this.checked) {
					column = parseInt( $(this).attr('data-column'), 10 ) + 1;
					styles = styles.concat( tsColSel.addSelectors( prefix, column ) );
				}
				$(this).toggleClass( wo.columnSelector_cssChecked, this.checked );
			});
			if (wo.columnSelector_mediaquery){
				colSel.$breakpoints.prop('disabled', true);
			}
			if (colSel.$style) {
				colSel.$style.prop('disabled', false).text( styles.length ? styles.join(',') + ' { display: none; }' : '' );
			}
			if (wo.columnSelector_saveColumns && ts.storage) {
				ts.storage( c.$table[0], 'tablesorter-columnSelector', colSel.states );
			}
			tsColSel.adjustColspans( c, wo );
			c.$table.triggerHandler(wo.columnSelector_updated);
		},

		setUpColspan: function(c, wo) {
			var index, span, nspace,
				$window = $( window ),
				hasSpans = false,
				$cells = c.$table
					.add( $(c.namespace + '_extra_table') )
					.children()
					.children('tr')
					.children('th, td'),
				len = $cells.length;
			for ( index = 0; index < len; index++ ) {
				span = $cells[ index ].colSpan;
				if ( span > 1 ) {
					hasSpans = true;
					$cells.eq( index )
						.addClass( c.namespace.slice( 1 ) + 'columnselectorHasSpan' )
						.attr( 'data-col-span', span );
					// add data-column values
					ts.computeColumnIndex( $cells.eq( index ).parent().addClass( 'hasSpan' ) );
				}
			}
			// only add resize end if using media queries
			if ( hasSpans && wo.columnSelector_mediaquery ) {
				nspace = c.namespace.slice( 1 ) + 'columnselector';
				// Setup window.resizeEnd event
				$window
					.off( nspace )
					.on( 'resize' + nspace, ts.window_resize )
					.on( 'resizeEnd' + nspace, function() {
						// IE calls resize when you modify content, so we have to unbind the resize event
						// so we don't end up with an infinite loop. we can rebind after we're done.
						$window.off( 'resize' + nspace, ts.window_resize );
						tsColSel.adjustColspans( c, wo );
						$window.on( 'resize' + nspace, ts.window_resize );
					});
			}
		},
		adjustColspans: function(c, wo) {
			var index, cols, col, span, end, $cell,
				colSel = c.selector,
				autoModeOn = colSel.auto,
				$colspans = $( c.namespace + 'columnselectorHasSpan' ),
				len = $colspans.length;
			if ( len ) {
				for ( index = 0; index < len; index++ ) {
					$cell = $colspans.eq(index);
					col = parseInt( $cell.attr('data-column'), 10 ) || $cell[0].cellIndex;
					span = parseInt( $cell.attr('data-col-span'), 10 );
					end = col + span;
					for ( cols = col; cols < end; cols++ ) {
						if ( !autoModeOn && colSel.states[ cols ] === false ||
							autoModeOn && c.$headerIndexed[ cols ] && !c.$headerIndexed[ cols ].is(':visible') ) {
							span--;
						}
					}
					if ( span ) {
						$cell.removeClass( wo.filter_filteredRow )[0].colSpan = span;
					} else {
						$cell.addClass( wo.filter_filteredRow );
					}
				}
			}
		},

		attachTo : function(table, elm) {
			table = $(table)[0];
			var colSel, wo, indx,
				c = table.config,
				$popup = $(elm);
			if ($popup.length && c) {
				if (!$popup.find('.tablesorter-column-selector').length) {
					// add a wrapper to add the selector into, in case the popup has other content
					$popup.append('<span class="tablesorter-column-selector"></span>');
				}
				colSel = c.selector;
				wo = c.widgetOptions;
				$popup.find('.tablesorter-column-selector')
					.html( colSel.$container.html() )
					.find('input').each(function(){
						var indx = $(this).attr('data-column'),
							isChecked = indx === 'auto' ? colSel.auto : colSel.states[indx];
						$(this)
							.toggleClass( wo.columnSelector_cssChecked, isChecked )
							.prop( 'checked', isChecked );
					});
				colSel.$popup = $popup.on('change', 'input', function(){
					// data input
					indx = $(this).toggleClass( wo.columnSelector_cssChecked, this.checked ).attr('data-column');
					// update original popup
					colSel.$container.find('input[data-column="' + indx + '"]')
						.prop('checked', this.checked)
						.trigger('change');
				});
			}
		}

	};

	/* Add window resizeEnd event (also used by scroller widget) */
	ts.window_resize = function() {
		if ( ts.timer_resize ) {
			clearTimeout( ts.timer_resize );
		}
		ts.timer_resize = setTimeout( function() {
			$( window ).trigger( 'resizeEnd' );
		}, 250 );
	};

	ts.addWidget({
		id: 'columnSelector',
		priority: 10,
		options: {
			// target the column selector markup
			columnSelector_container : null,
			// column status, true = display, false = hide
			// disable = do not display on list
			columnSelector_columns : {},
			// remember selected columns
			columnSelector_saveColumns: true,

			// container layout
			columnSelector_layout : '<label><input type="checkbox">{name}</label>',
			// data attribute containing column name to use in the selector container
			columnSelector_name  : 'data-selector-name',

			/* Responsive Media Query settings */
			// enable/disable mediaquery breakpoints
			columnSelector_mediaquery: true,
			// toggle checkbox name
			columnSelector_mediaqueryName: 'Auto: ',
			// breakpoints checkbox initial setting
			columnSelector_mediaqueryState: true,
			// hide columnSelector false columns while in auto mode
			columnSelector_mediaqueryHidden: false,
			// responsive table hides columns with priority 1-6 at these breakpoints
			// see http://view.jquerymobile.com/1.3.2/dist/demos/widgets/table-column-toggle/#Applyingapresetbreakpoint
			// *** set to false to disable ***
			columnSelector_breakpoints : [ '20em', '30em', '40em', '50em', '60em', '70em' ],
			// data attribute containing column priority
			// duplicates how jQuery mobile uses priorities:
			// http://view.jquerymobile.com/1.3.2/dist/demos/widgets/table-column-toggle/
			columnSelector_priority : 'data-priority',
			// class name added to checked checkboxes - this fixes an issue with Chrome not updating FontAwesome
			// applied icons; use this class name (input.checked) instead of input:checked
			columnSelector_cssChecked : 'checked',
			// event triggered when columnSelector completes
			columnSelector_updated : 'columnUpdate'

		},
		init: function(table, thisWidget, c, wo) {
			tsColSel.init(table, c, wo);
		},
		remove: function(table, c, wo, refreshing) {
			var csel = c.selector;
			if ( csel) { csel.$container.empty(); }
			if ( refreshing || !csel ) { return; }
			if (csel.$popup) { csel.$popup.empty(); }
			csel.$style.remove();
			csel.$breakpoints.remove();
			$( c.namespace + 'columnselectorHasSpan' ).removeClass( wo.filter_filteredRow );
			c.$table.off('updateAll' + namespace + ' update' + namespace);
		}

	});

})(jQuery);
