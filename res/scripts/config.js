const API_BASE_URL = "./api/index.php";

requirejs.config({
	baseUrl: "res/scripts",
	waitSeconds: 0,
	paths: {
		"components": "../../components",
		"modules": "../../modules",
		"logic": "../../logic",

		"axios": "lib/axios.min",
		"dayjs": "lib/dayjs.min",
		"dayjs-es": "lib/dayjs-es.min",
		"dayjs-relativeTime": "lib/dayjs-relativeTime.min",
		"dayjs-utc": "lib/dayjs-utc.min",
		"text": "lib/text",
		"tiny-emitter": "lib/tinyemitter.min",
		"vue": "lib/vue.global.prod",
		"vue-router": "lib/vue-router.global.prod",
	},
	shim: {
		"vue": {
			exports: "Vue"
		},
		"vue-router": {
			deps: ["vue"],
			exports: "VueRouter"
		}
	}
});