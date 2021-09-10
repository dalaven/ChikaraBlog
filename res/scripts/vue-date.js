define([
	"dayjs",
	"dayjs-relativeTime",
	"dayjs-utc",
	"dayjs-es",
], (dayjs, relativeTime, utc) => {

	dayjs.extend(relativeTime);
	dayjs.extend(utc);
	dayjs.locale("es");

	return {
		install: app => {
			app.config.globalProperties.$dayjs = (date) => {
				return dayjs(date);
			};
			app.config.globalProperties.$toDatetimeLocalValue = (date) => {
				return date && dayjs(date).format("YYYY-MM-DD[T]HH:mm");
			};
			app.config.globalProperties.$toDateValue = (date) => {
				return date && dayjs(date).format("YYYY-MM-DD");
			};
			app.config.globalProperties.$toDate = (value) => {
				return value && dayjs(value).toDate();
			};
			app.config.globalProperties.$formatDate = (value, format) => {
				return value && dayjs(value).format(format);
			};
		}
	};
});
