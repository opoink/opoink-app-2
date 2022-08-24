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
				top_buttons: null,
				setListingName: (listingName) => {
					this.listingName = listingName;
					return this;
				},
				getListingName: () => {
					return this.listingName;
				},
				actionBtns: () => {
					$('.action-btns').on('click', (e) => {
						e.preventDefault();

						let dataset = e.currentTarget.dataset;

						try {
							if(dataset.type == 'action-confirm'){
								$('#confirmationModal .modal-body').empty();
								$('#confirmationModal .modal-body').append(dataset.content);
								$('#confirmationModal #confirmationModalLabel').text(dataset.modal_title);
								$('#confirmationModal .modal-footer .btn-secondary').text(dataset.modal_secondary);
								$('#confirmationModal .modal-footer .btn-primary').text(dataset.modal_primary);
								$('#confirmationModal .modal-footer .btn-primary').unbind().on('click', function(){
									window.location.href = dataset.action_url;
									$('#confirmationModal').modal('hide');
								});
								$('#confirmationModal').modal('show');
							}
						} catch (error) {

						}
					});
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
							this.top_buttons = result.top_buttons;

							setTimeout(() => {
								this.actionBtns();
							}, 100);
						}
						$('#main-page-loader').addClass('d-none');
					}).catch(error => {
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
				},
				setOrderBy: (column) => {
					if(column.sortable){
						this.filters.filters.page = 1;
						if(typeof this.filters.filters.sort_order == 'undefined'){
							this.filters.filters.sort_order = {};
						}
	
						this.filters.filters.sort_order['order_by'] = column.column_name;
	
						if(typeof this.filters.filters.sort_order.direction == 'undefined'){
							this.filters.filters.sort_order['direction'] = 'asc';
						}
						else {
							if(this.filters.filters.sort_order['direction'] == 'asc'){
								this.filters.filters.sort_order['direction'] = 'desc';
							}
							else {
								this.filters.filters.sort_order['direction'] = 'asc';
							}
						}
						
						this.goToPage();
					}
				},
				resetFieldValues: () => {
					var inputs = $('#admin-grid-filters-modal input');
					$.each(inputs, (key, val) => {
						$(val).val('');
					});

					var select = $('#admin-grid-filters-modal select');
					$.each(select, (key, val) => {
						$(val).val('');
					});
				},
				showFiltersModal: () => {
					$('#admin-grid-filters-modal').modal('show');
					$('#admin-grid-filters-modal').on('hide.bs.modal', (e) => {
						this.tmpSearchFields = {};
					});

					this.resetFieldValues();

					setTimeout(() => {
						$.each(this.filters.filters.search_fields, (key, val) => {
							this.tmpSearchFields[val['field']] = val;
							if(val.type == 'text' || val.type == 'select'){
								$('#admin-grid-filter-' + val['field']).val(val['search_string']);
							}
							else if(val.type == 'range') {
								if(typeof val['from'] != 'undefined'){
									$('#admin-grid-filter-' + val['field'] + '-from').val(val['from']);
								}
								if(typeof val['to'] != 'undefined'){
									$('#admin-grid-filter-' + val['field'] + '-to').val(val['to']);
								}
							}
						});
					}, 100);


				},
				exportedCsvFiles: null,
				showDownloadCSVFilesModal: () => {
					$('#admin-grid-exported-csv-files-modal').modal('show');
					req.doRequest(adminUrl + 'grid/export/csvfiles?listing_name='+this.getListingName(), '', 'GET')
					.then(result => {
						this.exportedCsvFiles = result;
						console.log('exportedCsvFiles exportedCsvFiles', this.exportedCsvFiles);
					}).catch(error => {

					});
				},
				tmpSearchFields: {},
				updateFilter(type, column, event, fromOrTo){
					if(type == 'text' || type == 'select'){
						if(event.target.value){
							this.tmpSearchFields[column.column_name] = {
								field: column.column_name,
								search_string: event.target.value,
								type: type,
								label: column.label
							};
						}
						else {
							if(typeof this.tmpSearchFields[column.column_name] != 'undefined') {
								delete this.tmpSearchFields[column.column_name];
							}
						}
					}
					else if(type == 'range'){
						if(typeof this.tmpSearchFields[column.column_name] == 'undefined'){
							this.tmpSearchFields[column.column_name] = {
								field: column.column_name,
								search_string: null,
								type: type,
								label: column.label
							};
						}
						if(event.target.value){
							this.tmpSearchFields[column.column_name][fromOrTo] = event.target.value;}
						else {
							if(typeof this.tmpSearchFields[column.column_name][fromOrTo] != 'undefined') {
								delete this.tmpSearchFields[column.column_name][fromOrTo];
							}
						}

						if(
							typeof this.tmpSearchFields[column.column_name]['from'] == 'undefined' && 
							typeof this.tmpSearchFields[column.column_name]['to'] == 'undefined'
						) {
							delete this.tmpSearchFields[column.column_name];
						}
					}
				},
				applyFilter(){
					this.filters.filters.search_fields = [];

					$.each(this.tmpSearchFields, (key, val) => {
						this.filters.filters.search_fields.push(val);
					});

					setTimeout(() => {
						this.filters.filters.page = 1;
						this.getListing();
						$('#admin-grid-filters-modal').modal('hide');
					}, 100);
				},
				removeFilter(search_field){
					var toRemoveKeys = [];
					$.each(this.filters.filters.search_fields, (key, val) => {
						if(search_field.field == val.field){
							toRemoveKeys.push(key);
						}
					});

					$.each(toRemoveKeys, (key, val) => {
						this.filters.filters.search_fields.splice(val, 1);
					});

					this.filters.filters.page = 1;
					setTimeout(() => {
						this.getListing();
					}, 100);
				},
				getOptionLabelBySearchField: (search_field) => {
					var optionLabel = "";
					$.each(this.columns, (key, val) => {
						if(val.column_name == search_field.field && typeof val.filter != 'undefined'){
							if(typeof val.filter.option != 'undefined'){
								$.each(val.filter.option, (key2, val2) => {
									if(val2.key == search_field.search_string){
										optionLabel = val2.value;
									}
								});
							}
						}
					});
					return optionLabel;
				},
				getExportedFileDownloadLink: (exportedFile) => {
					if(exportedFile.status == 'done'){
						return adminUrl + 'grid/export/download?export_id='+exportedFile.grid_listing_export_id;
					}
					else {
						return 'javascript:void(0)';
					}
				}
			}
		},
		mounted: function(){
			$('#admin-grid-table').removeClass('d-none');
		}
	}).$mount('#admin-grid-table');
	return grid;
});