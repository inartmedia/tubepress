jQuery.getScript = function(url, callback, cache) {
    jQuery.ajax({ type: "GET", url: url, success: callback, dataType: "script", cache: cache }); 
}; 

function tubepress_init(baseUrl) {
	tubepress_load_embedded_js(baseUrl);
    tubepress_load_players(baseUrl);
    tubepress_attach_listeners();
}

function tubepress_attach_listeners()
{
	jQuery("a[id^='tubepress_']").click(function () {
		var rel_split    = jQuery(this).attr("rel").split("_");
		var galleryId    = rel_split[3];
		var playerName   = rel_split[2];
		var embeddedName = rel_split[1];
		var videoId = jQuery(this).attr("id").substring(16, 27);

        _tubepress_swap_embedded(galleryId, videoId, embeddedName);
        _tubepress_call_player_js(galleryId, videoId, embeddedName, playerName);
    });
}

function _tubepress_swap_embedded(galleryId, videoId, embeddedName) {
    var matcher = window["tubepress_" + embeddedName + "_matcher"]();
    var wrapper = jQuery("#tubepress_embedded_object_" + galleryId);
    if (wrapper.html() != null) {
        var oldVideoId = wrapper.html().match(matcher)[1];
        wrapper.html(wrapper.html().replace(oldVideoId, videoId));
    }
}

function _tubepress_call_player_js(galleryId, videoId, embeddedName, playerName) {
    var playerFunctionName = "tubepress_" + playerName + "_player";
    window[playerFunctionName](galleryId, videoId);
}


function tubepress_load_players(baseUrl) {
	var playerNames = _tubepress_rel_parser(2);
    for(var i = 0; i < playerNames.length; i++) {
    	var name = playerNames[i];
        jQuery.getScript(baseUrl + "/ui/players/" + name + "/" + name + ".js", 
        	_tubepress_player_loaded(name, baseUrl) , true);
    }
}

function _tubepress_player_loaded(playerName, baseUrl) {
	var funcName = 'tubepress_' + playerName + '_player_init';
	var f = function() { window[funcName](baseUrl); }
	_tubepress_call_when_true(function() { return typeof window[funcName] == 'function'; }, f);
}

function tubepress_load_embedded_js(baseUrl) {
    var embeddedNames = _tubepress_rel_parser(1);
    for(var i = 0; i < embeddedNames.length; i++) {
        jQuery.getScript(baseUrl + "/ui/embedded/" + embeddedNames[i] + "/" + embeddedNames[i] + ".js", function() {}, true);
    }
}

function _tubepress_rel_parser(index) {
    var returnValue = [];
    jQuery("a[rel^='tubepress_']").each( function() {
        var thisName = jQuery(this).attr("rel").split("_")[index];
        if (jQuery.inArray(thisName, returnValue) == -1) {
            returnValue.push(thisName);
        }
    });
    return returnValue;
}

function _tubepress_call_when_true(test, callback) {
	if (!test()) {
		setTimeout(function() {_tubepress_call_when_true(test, callback);}, 400);
		return;
	}
	callback();
}

function _tubepress_get_wait_call(scriptPath, test, callback) {
	jQuery.getScript(scriptPath, function() {
		_tubepress_call_when_true(test, callback);
    }, true);
}