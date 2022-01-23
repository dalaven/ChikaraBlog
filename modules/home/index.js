define([
	"text!./index.html",
	"components/menu"
], (html, Menu) => {
	return {
		template: html,
		components: {
			Menu
		},
		data() {
			return {

			};
		},
		computed: {

		},
		methods: {
			openLink(media){
				if(media === 'fb'){
					window.open('https://www.facebook.com/ChikaraOficial', '_blank')
				}
				if(media === 'ig'){
					window.open('https://www.instagram.com/chikaraoficial/', '_blank')
				}
				if(media === 'yt'){
					window.open('https://www.youtube.com/channel/UCG51dRdn45UfK58rMeqrCPw', '_blank')
				}
			}

		},
		created() {
		},
	};
});