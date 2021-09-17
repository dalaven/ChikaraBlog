define([
	"loader",
	"local",
	"vue-router",
], (loader, local, VueRouter) => {

	const m = loader("modules");

	const router = VueRouter.createRouter({
		history: VueRouter.createWebHashHistory(),
		routes: [
			{ name: "index", path: "/", component: m("index"), meta: { title: '' } },
			{ name: "home", path: "/home", component: m("home/index"), meta: { title: 'Home' } },
		]
	});

	router.afterEach((to, from) => {
		document.title = to.meta.title + " | CHIKARA";
	});

	return router;
});