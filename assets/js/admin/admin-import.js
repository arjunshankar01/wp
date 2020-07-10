const ImportModule = {};

jQuery(document).ready(($) => {

	var itemTotal,
		itemIndex = 0,
		batchSize = 1,
		persist,
		itemsBeingProcessed = [],
		itemsCompleted = [],
		itemsFailed = [],
		$domStatusCounter,
		ajaxUrl = drgc_admin_params.ajax_url,
		ajax_nonce = drgc_admin_params.ajax_nonce,
		instance_id = drgc_admin_params.drgc_ajx_instance_id,
		siteID = drgc_admin_params.site_id,
		apiKey = drgc_admin_params.api_key,
		$progressBar = $('#dr-data-process-progressbar'),
		$fecounter = $('.wrapImportControls p'),
		$importButton = $('#products-import-btn');

	$('#products-import-btn').on( 'click', function( e ) {
		e.preventDefault();

		if ( ! siteID || ! apiKey ) {
			return alert( 'Please provide siteID & apiKey!' );
		}

		$importButton.attr( 'disabled', 'disabled' );
		if ($('.notice').is(':visible')) {
			$('.notice').hide();
		}

		$('.wrapImportControls').append("<h4> <b>Fetching products, locales and currencies...</b> </h4>");

		var data = {
			action      : 'drgc_ajx_action',
			nonce       : ajax_nonce,
			instance_id : instance_id,
			step        : 'init',
		};

		$.ajax( {
			dataType    : 'json',
			data        : data,
			type        : 'post',
			url         : ajaxUrl,
			context     : this,
			nonce       : ajax_nonce,
			success     : ajaxInitSuccess
		} );
	});

	function ajaxInitSuccess( data, textStatus, jqXHR ) {
		itemTotal = data.entries_count;
		batchSize = data.batch_size;
		itemIndex = data.index_start;

		$('.wrapImportControls').find('h4').remove();

		$progressBar.show();
		$progressBar.progressbar({ max: itemTotal, value: 0 });
		$fecounter.show();
		$importButton.hide();
		updateTotal( itemTotal );
		processNext();
	}

	function processNext() {
		var counter = 0,
			data;

		while ( counter < batchSize && itemIndex < itemTotal ) {
			itemsBeingProcessed.push( itemIndex );
			itemIndex++;
			counter++;
		}

		if ( !itemsBeingProcessed.length ) {
			complete();
			return;
		}

		data = {
			action              : 'drgc_ajx_action',
			step                : 'batchprocess',
			persist             : persist,
			nonce               : ajax_nonce,
			instance_id         : instance_id,
			itemsBeingProcessed : itemsBeingProcessed
		};

		$.ajax( {
			dataType    : 'json',
			data        : data,
			type        : 'post',
			url         : ajaxUrl,
			context     : this,
			nonce       : ajax_nonce,
			success     : ajaxBatchSuccess
		} );
	}

	function ajaxBatchSuccess( data, textStatus, jqXHR ) {
		var lastIndex;

		$.each( data.results, function( key, value ) {
			if ( value.success ) {
				itemsCompleted.push( key );
			} else if ( 'failure' === value ) {
				itemsFailed.push( key );
			}
			lastIndex = key;
		});

		updateStatus( itemIndex );
		itemsBeingProcessed = [];

		persist = data.results[ lastIndex ]['persist'];

		processNext();
	}

	function updateStatus( numberProcessed ) {
		if ( !$domStatusCounter ) {
			$domStatusCounter = $('#dr-data-process-counter' );
		}

		$progressBar.progressbar( "option", "value", numberProcessed );
		$domStatusCounter.text( numberProcessed );
	}

	function updateTotal( numberTotal ) {
		$('#dr-data-process-total').text( numberTotal );
	}

	function complete() {
		$progressBar.hide();
		$('.wrapImportControls').html("<h3><b>Cleaning up...</b></h3>");

		var data = {
			action      : 'drgc_ajx_action',
			step        : 'end',
			persist     : persist,
			nonce       : ajax_nonce,
			instance_id : instance_id
		};

		$.ajax( {
			dataType    : 'json',
			data        : data,
			type        : 'post',
			url         : ajaxUrl,
			context     : this,
			success     : ajaxCompleteSuccess
		} );
	}

	function ajaxCompleteSuccess( data, textStatus, jqXHR ) {
		window.location.href = data.url;
	}

});

export default ImportModule;
