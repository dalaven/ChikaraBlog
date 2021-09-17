require([
	"app",
	"router"
], (app, router) => {
	
	app.use(router).mount("#app");

});