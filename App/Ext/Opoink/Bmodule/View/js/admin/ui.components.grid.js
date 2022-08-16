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
				setListingName: (listingName) => {
					this.listingName = listingName;
					return this;
				},
				getListingName: () => {
					return this.listingName;
				},
				getListing: () => {
					req.doRequest(adminUrl + 'uicomponents/grid/listing?listing_name='+this.getListingName(), JSON.stringify(this.filters), 'POST')
					.then(result => {
						if(typeof result.columns != 'undefined'){
							this.columns = result.columns;
							this.list_data = result.list_data;
							this.filters = result.filters;
						}
						console.log('columns columns columns', this.columns);
						console.log('list_data list_data list_data', this.list_data);
						console.log('filters filters filters', this.filters);
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