function probarBox() {
	this.loadImg = function(data, object, time) {
		if (! time)
			time = 50;
		var i = 0.01;
		var set = setInterval(function() {
			i += i--;
			if (i>data) {
				i = data;
			}
			object.innerHTML = i;
		}, time);
		if ( i == data)
			clearInterval(set);
	}
}