define(() => {

	//let cache = [];
	let hooks = [];

	function broadcast(key, value) {
		hooks.filter(h => h.key === "*" || h.key === key).forEach(h => h.callback(value));
	}

	function local(key, value) {
		if (key === null) {
			localStorage.clear();
			//cache = [];
			return;
		}

		if (value === undefined) {
			return key in localStorage ? JSON.parse(localStorage[key]) : null
			/*cache[key]
						if (key in cache) {
						} else {
							return cache[key] = key in localStorage ? JSON.parse(localStorage[key]) : null;
						}*/
		} else {
			if (value === null) {
				delete localStorage[key];
				//	delete cache[key];
			} else {
				localStorage[key] = JSON.stringify(value);
				//	cache[key] = value;
			}

			broadcast(key, value);
			return value;
		}
	}

	local.hook = function (key, callback) {
		hooks.push({ key, callback });
	};

	return local;
});
