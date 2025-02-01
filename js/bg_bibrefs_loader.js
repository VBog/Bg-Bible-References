(function(){
	if(window.innerWidth < 520) return;
	
	let el = document.createElement("script");
	el.src = bg_bibrefs.main_script;
	el.defer = true;
	document.body.append(el);
})();