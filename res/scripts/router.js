define([
	"loader",
	"local",
	"vue-router",
], (loader, local, VueRouter) => {

	const m = loader("modules");

	const router = VueRouter.createRouter({
		history: VueRouter.createWebHashHistory(),
		routes: [
			//{ name: "index", path: "/", component: m("index"), meta: { title: '' } },
			{ name: "home", path: "/", component: m("home/index"), meta: { title: 'Home' } },
			{ name: "register", path: "/admin/register", component: m("enroll/index"), meta: { title: 'Inscripción' } },
			{ name: "register:admin:course", path: "/admin/curso", component: m("enroll/course-admin"), meta: { title: 'Inscripción' } },
			{ name: "register:course", path: "/registro", component: m("enroll/course"), meta: { title: 'Inscripción' } },
			{ name: "game", path: "/games", component: m("games/index"), meta: { title: 'Juegos' } },
		]
	});

	router.afterEach((to, from) => {
		document.title = to.meta.title + " | CHIKARA";
	});

	return router;
});