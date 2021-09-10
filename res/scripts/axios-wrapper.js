define([
	"axios"
], (axios) => {

	function each(value, callback) {
		if (Array.isArray(value)) {
			for (let i = 0; i < value.length; i++) {
				callback.call(value, value[i], i, value.length);
			}
		} else if (typeof value === "object") {
			for (let k in value) {
				callback.call(value, value[k], k);
			}
		}
	}

	function walk(object, callback) {
		each(object, function(value, k){
			callback.call(this, value, k);

			if (Array.isArray(value) || typeof value === "object") {
				walk(value, callback);
			}
		});
	}

	function defaultDateParser(res) {
		walk(res.data, function(v, k){
			if (typeof v === "string" && /^(\d{4})-(\d{2})-(\d{2})T(\d{2}):(\d{2}):(\d{2})(\.\d+)?Z/.test(v)) {
				this[k] = new Date(Date.parse(v));
			}
		});
		return res;
	}

	function showErrors(err) {
		if (err.config.displayError && err.response.data && err.response.data.message) {
			alert(err.response.data.message);
		}
		
		return Promise.reject(err);
	}

	function resource(instance, base, url, query, options, onload) {
		base.isLoading = false;
		base.isSuccessful = false;
		base.isFailed = false;

		base.update = query => {
			base.isLoading = true;
			base.isSuccessful = false;
			base.isFailed = false;

			return base.ready = new Promise((resolve, reject) => {
				instance.get(url, {params: query})
					.then(data => {
						onload(data, query);
						base.isLoading = false;
						base.isSuccessful = true;
						resolve(data);
					})
					.catch(err => {
						base.isLoading = false;
						base.isFailed = true;
						reject(err);
					})
				;
			});
		};
		
		if (!options.delay) {
			base.update(query);
		}
	}
	
	function axiosWrapper(baseURL, {handleDates = true} = {}) {
		let instance = axios.create({baseURL});
		let {get, post, delete: del} = instance;

		instance.interceptors.response.use(null, showErrors);

		if (handleDates) {
			instance.interceptors.response.use(defaultDateParser);
		}
	
		instance.get = function() {
			return get.apply(instance, arguments).then(r => r.data);
		};
	
		instance.post = function() {
			return post.apply(instance, arguments).then(r => r.data);
		};
	
		instance.delete = function() {
			return del.apply(instance, arguments).then(r => r.data);
		};

		instance.resource = function(url, query, options = {}) {
			let res = {data: null};

			resource(instance, res, url, query, options, data => {
				res.data = data;
			});

			return res;
		};
		
		instance.listResource = function(url, originalQuery, options = {}) {
			let res = [];
			let page = 1;

			resource(instance, res, url, originalQuery, options, (data, query) => {
				res.lastChunkLength = data.length;

				if (query && (query.beforeId || query.page)) {
					res.splice.apply(res, [res.length, 0].concat(data));
				} else {
					page = 1;
					res.splice.apply(res, [0, res.length].concat(data));
				}
			});

			res.appendNextPage = () => {
				let args = {};
				
				if (options.appendMode === "page") {
					args.page = ++page;
				} else {
					args.beforeId = res[res.length - 1].id;
				}

				// Trigger Vue reactivity
				res.splice(res.length, 0);

				return res.update({
					...originalQuery,
					...args
				});
			};

			return res;
		};
	
		return instance;
	}

	return axiosWrapper;
});