define([
	'jquery',
	'request',
	'vue'
], function($, req, Vue) {

	var grid = new Vue({
		data() {
			return {
				listingName: '',
				columns: null,
				list_data: null,
				filters: {},
				limits: null,
				setListingName: (listingName) => {
					this.listingName = listingName;
					return this;
				},
				getListingName: () => {
					return this.listingName;
				},
				getListing: () => {
					$('#main-page-loader').removeClass('d-none');
					req.doRequest(adminUrl + 'uicomponents/grid/listing?listing_name='+this.getListingName(), JSON.stringify(this.filters), 'POST')
					.then(result => {
						if(typeof result.columns != 'undefined'){
							this.columns = result.columns;
							this.list_data = result.list_data;
							this.filters = result.filters;
							this.limits = result.limits;
						}
						$('#main-page-loader').addClass('d-none');
					});
				},
				goToPrevPage: () => {
					if(parseInt(this.list_data.current_page) > 1){
						this.filters.filters.page = parseInt(this.list_data.current_page) - 1;
						this.getListing();
					}
				},
				goToNextPage: () => {
					if(parseInt(this.list_data.total_page) > parseInt(this.list_data.current_page)){
						this.filters.filters.page = parseInt(this.list_data.current_page) + 1;
						this.getListing();
					}
				},
				goToPage: () => {
					if(parseInt(this.filters.filters.page) >= 1 && parseInt(this.filters.filters.page) <= parseInt(this.list_data.total_page)){
						this.filters.filters.page = parseInt(this.filters.filters.page);
						this.getListing();
					}
					else {
						this.filters.filters.page = this.list_data.current_page;
					}
				},
				changeLimitPerPage: () => {
					this.filters.filters.page = 1;
					this.filters.filters.limit = parseInt(this.filters.filters.limit);
					this.goToPage();
				}
			}
		},
		mounted: function(){
			$('#admin-grid-table').removeClass('d-none');
		}
	}).$mount('#admin-grid-table');

	console.log('grid grid', grid);

	return grid;
});