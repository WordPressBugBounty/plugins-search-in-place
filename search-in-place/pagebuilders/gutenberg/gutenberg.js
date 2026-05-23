jQuery(function(){
	( function( blocks, element ) {
		var el = element.createElement,
			InspectorControls = wp.blockEditor.InspectorControls,
			ServerSideRender = wp.serverSideRender;

		/* Plugin Category */
		blocks.getCategories().push({slug: 'searchinplace', title: 'Search in Place'});

		/* ICONS */
		const iconSIP = el('img', { width: 20, height: 20, src:  "data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbDpzcGFjZT0icHJlc2VydmUiIHN0eWxlPSJmaWxsLXJ1bGU6ZXZlbm9kZDtjbGlwLXJ1bGU6ZXZlbm9kZDtzdHJva2UtbGluZWpvaW46cm91bmQ7c3Ryb2tlLW1pdGVybGltaXQ6MS41IiB2aWV3Qm94PSIwIDAgNDggNDgiPjxnIGlkPSJTVkdSZXBvX2ljb25DYXJyaWVyIj48Y2lyY2xlIGN4PSIxMy42NjkiIGN5PSIxMy44OTQiIHI9IjEwLjEwNiIgc3R5bGU9ImZpbGw6bm9uZTtzdHJva2U6IzE1YWZlYTtzdHJva2Utd2lkdGg6My44OHB4IiB0cmFuc2Zvcm09Im1hdHJpeCgxLjE4NDA1IDAgMCAxLjE3NTc0IC0uNjU2IC0uNjY2KSIvPjxwYXRoIGQ9Ik0zNC4wMTkgMjIuMDI2VjQyLjkyYTEuMTUgMS4xNSAwIDAgMS0xLjE1MSAxLjE1MWgtMi4zMDFhMS4xNSAxLjE1IDAgMCAxLTEuMTUxLTEuMTUxVjIyLjAyNmMwLS42MzUuNTE1LTEuMTUxIDEuMTUxLTEuMTUxaDIuMzAxYy42MzYgMCAxLjE1MS41MTYgMS4xNTEgMS4xNTEiIHN0eWxlPSJmaWxsOiMxNWFmZWEiIHRyYW5zZm9ybT0ibWF0cml4KC44MzcyNSAtLjgzMTM3IC44MzcyNSAuODMxMzcgLTE5LjY3NSAzMy4wMzUpIi8+PHBhdGggZD0ibTE1LjUyOSAxMS41NzkgMy4xNjktMi44NnM2LjM2MSAzLjk4OCA0LjI5OCA4LjUwMmMtLjA5MS4wOTEtMS4zNzYtNi4xNDgtNy40NjctNS42NDIiIHN0eWxlPSJmaWxsOiMxNWFmZWE7ZmlsbC1vcGFjaXR5Oi40IiB0cmFuc2Zvcm09InRyYW5zbGF0ZSgtMTIuNjU2IC00LjM1NSlzY2FsZSgxLjQ0NzcyKSIvPjwvZz48L3N2Zz4=" } );

		blocks.registerBlockType( 'searchinplace/sip', {
			title: 'Search in Place',
			icon: iconSIP,
			category: 'searchinplace',
			supports: {
				customClassName: false,
				className: false
			},

			attributes: {
                search_in_sections : {
                    type 	: 'text',
                    default : ''
                },
				placeholder : {
					type 	: 'text',
					default : ''
				},
				search_in_page : {
					type 	: 'integer',
					default : 0
				},
				autocomplete : {
					type 	: 'integer',
					default : 0
				},
				disable_enter_key : {
					type 	: 'integer',
					default : 0
				},
				no_popup : {
					type 	: 'integer',
					default : 0
				},
				exclude_hidden : {
					type 	: 'integer',
					default : 0
				},
				display_button : {
					type 	: 'integer',
					default : 0
				},
			},

			edit: function( props ){
				var focus = props.isSelected,
					children = [
						el(
							ServerSideRender,
							{
								key : 'sip_server',
								block: 'searchinplace/sip',
								attributes: props.attributes,
							}
						)
					];


				if(!!focus)
				{
					children.push(
						el(
							InspectorControls,
							{
								key: 'sip_inspector'
							},
							el(
								'div',
								{
									key 	 : 'search_in_place_container',
									className: 'components-panel__body is-opened'
								},
								[
									el(
										'label',
										{
											key : 'sip_placeholder_label',
										},
										'Placeholder text'
									),
									el(
										'input',
										{
											type 	: 'text',
											key 	: 'sip_placeholder',
											value	: props.attributes.placeholder,
											onChange: function(evt){
												props.setAttributes(
													{placeholder: evt.target.value}
												);
											},
                                            style:{width:'100%'},
										},
									),
									el( 'div', {key: 'sip_br_5', style:{marginBottom: '10px'}}),
                                    el(
										'input',
										{
											type 	: 'checkbox',
											key 	: 'sip_search_in_page',
											checked	: (props.attributes.search_in_page == 1),
											onChange: function(evt){
												props.setAttributes(
													{search_in_page: (evt.target.checked ? 1 : 0)}
												);
											},
                                        },
									),
									el(
										'label',
										{
											key : 'sip_search_in_page_label',
										},
										'Search in current page only'
									),
									el( 'div', {key: 'sip_br_6', style:{marginBottom: '10px'}}),
                                    el(
										'input',
										{
											type 	: 'checkbox',
											key 	: 'sip_autocomplete',
											checked	: (props.attributes.autocomplete == 1),
											onChange: function(evt){
												props.setAttributes(
													{autocomplete: (evt.target.checked ? 1 : 0)}
												);
											},
                                        },
									),
									el(
										'label',
										{
											key : 'sip_autocomplete_label',
										},
										'Activate the autocomplete for search in current page'
									),
									el( 'div', {key: 'sip_br_4', style:{marginBottom: '10px'}}),
									el(
										'input',
										{
											type 	: 'checkbox',
											key 	: 'sip_disable_enter_key',
											checked	: (props.attributes.disable_enter_key == 1),
											onChange: function(evt){
												props.setAttributes(
													{disable_enter_key: (evt.target.checked ? 1 : 0)}
												);
											},
										},
									),
									el(
										'label',
										{
											key : 'sip_disable_enter_key_label',
										},
										'Disable enter key'
									),
									el( 'div', {key: 'sip_br', style:{marginBottom: '10px'}}),
									el(
										'input',
										{
											type 	: 'checkbox',
											key 	: 'sip_no_popup',
											checked	: (props.attributes.no_popup == 1),
											onChange: function(evt){
												props.setAttributes(
													{no_popup: (evt.target.checked ? 1 : 0)}
												);
											},
										},
									),
									el(
										'label',
										{
											key : 'sip_no_popup_label'
										},
										'Hide results pop-up (affects the search in current page only)'
									),
									el( 'div', {key: 'sip_br_2', style:{marginBottom: '10px'}}),
									el(
										'input',
										{
											type 	: 'checkbox',
											key 	: 'sip_exclude_hidden',
											checked	: (props.attributes.exclude_hidden == 1),
											onChange: function(evt){
												props.setAttributes(
													{exclude_hidden: (evt.target.checked ? 1 : 0)}
												);
											},
										},
									),
									el(
										'label',
										{
											key : 'sip_exclude_hidden_label'
										},
										'Exclude hidden terms on page (affects the search in current page only)'
									),
									el( 'div', {key: 'sip_br_3', style:{marginBottom: '10px'}}),
									el(
										'input',
										{
											type 	: 'checkbox',
											key 	: 'sip_display_button',
											checked	: (props.attributes.display_button == 1),
											onChange: function(evt){
												props.setAttributes(
													{display_button: (evt.target.checked ? 1 : 0)}
												);
											},
										},
									),
									el(
										'label',
										{
											key : 'sip_display_button_label'
										},
										'Display the search button (affects the search in current page only)'
									),
                                    el(
                                        'label',
                                        {
                                            key: 'sip_search_in_sections_label',
                                            style: { marginTop: '10px', display: 'block' }
                                        },
                                        'Search in sections'
                                    ),
                                    el(
                                        'input',
                                        {
                                            type: 'text',
                                            key: 'sip_search_in_sections',
                                            value: props.attributes.search_in_sections,
                                            onChange: function (evt) {
                                                props.setAttributes(
                                                    { search_in_sections: evt.target.value }
                                                );
                                            },
                                            style: { width: '100%' },
                                        },
                                    ),
                                    el( 'span', {key: 'sip_search_in_sections_desc', style:{fontSize: '12px', color: '#666'}}, 'Comma separated list of CSS selectors to limit the search in specific sections of the page. If empty, the whole page will be searched.' )
								]
							)
						)
					);
				}

				return 	children;
			},

			save: function( props ) {
				var shortcode = '[search-in-place-form';
                if(props.attributes.placeholder && props.attributes.placeholder.length)
                    shortcode += ' placeholder="'+props.attributes.placeholder.replace(/"/g, '\"')+'"';
				if(props.attributes.search_in_page) shortcode += ' in_current_page="1"';
				if(props.attributes.autocomplete) shortcode += ' autocomplete="1"';
				if(props.attributes.disable_enter_key) shortcode += ' disable_enter_key="1"';
				if(props.attributes.no_popup) shortcode += ' no_popup="1"';
				if(props.attributes.exclude_hidden) shortcode += ' exclude_hidden_terms="1"';
				if(props.attributes.display_button) shortcode += ' display_button="1"';
                if (props.attributes.search_in_sections && props.attributes.search_in_sections.length)
                    shortcode += ' search_in_sections="' + props.attributes.search_in_sections.replace(/"/g, '\"') + '"';
				shortcode += ']';
				return el( 'div', null, shortcode );
			}
		});
	} )(
		window.wp.blocks,
		window.wp.element
	);
});