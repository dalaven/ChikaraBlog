require([
	"app",
	"router"
], (app, router) => {
	console.log(router.currentRoute.value);

	app.use(router).mount("#app");

});