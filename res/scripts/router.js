define([
	"loader",
	"local",
	"vue-router",
], (loader, local, VueRouter) => {

	const m = loader("modules");

	const router = VueRouter.createRouter({
		history: VueRouter.createWebHashHistory(),
		routes: [
			{ name: "index", path: "/", component: m("index") },
			{ name: "home", path: "/home", component: m("home/index") },
		]
	});

	return router;
});