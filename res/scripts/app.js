define([
	"modules/layout",
	"vue",
	"vue-date",
	"vue-util"
], (Layout, Vue, VueDate, VueUtil) => {
	
	const app = Vue.createApp(Layout);

	app
		.use(VueDate)
		.use(VueUtil)
		.provide("ui", {
			strings: {
				add: "Agregar",
				back: "Volver",
				close: "",
				delete: "Eliminar",
				edit: "Editar",
				form: {
					field: {
						validationError: "Éste campo debe ser llenado correctamente"
					}
				},
				item: {
					singular: "1 elemento",
					plural: "# elementos",
				},
				list: {
					empty: "No hay elementos para mostrar"
				},
				previous: "Anterior",
				next: "Siguiente",
			},	
		})
	;

	return app;
});