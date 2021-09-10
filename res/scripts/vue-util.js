define([
	"tiny-emitter"
], (Emitter) => {
	return {
		install: app => {
			app.directive("focus", {
				mounted: el => {
					el.focus();
				}
			});

			app.mixin({
				methods: {
					$on(e, fn) {
						return this.$emitter.on(e, fn, this);
					},
					$once(e, fn) {
						return this.$emitter.once(e, fn, this);
					},
					$off(e, fn) {
						return this.$emitter.off(e, fn);
					},
					$trigger(...args) {
						this.$emit(...args);
						return this.$emitter.emit(...args);
					}
				},
				created() {
					this.$emitter = new Emitter();
				}
			});
		}
	};
	
});