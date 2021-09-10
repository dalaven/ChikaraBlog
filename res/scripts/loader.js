define(() => {

	function loadModule(name) {
		return new Promise((resolve, reject) => {
			require([name], resolve, reject);
		});
	}
	
	function loadComponent(name) {
		return () => loadModule(name);
	}

	function factory(base, loading, error) {
		return name => {
			return loadComponent(`${base}/${name}`, loading, error);
		};
	}

	return factory;
});