const inputLat = document.querySelector('#addtask-lat');
const inputLng = document.querySelector('#addtask-lng');

const autoCompleteJS = new autoComplete({
    wrapper: false,
    searchEngine: 'loose',
	data: {
		src: async () => {
			try {
				// Fetch External Data Source
				const source = await fetch(`/autocomplete/${query}`);
				const data = await source.json();
				// Returns Fetched data
				return data;
			} catch (error) {
				return error;
			}
		},

		keys: ['location'],
    cache: true,
	},
	resultsList: {
		element: (list, data) => {
			const info = document.createElement("p");
			if (data.results.length > 0) {
				info.innerHTML = `Displaying <strong>${data.results.length}</strong> out of <strong>${data.matches.length}</strong> results`;
			} else {
				info.innerHTML = `Found <strong>${data.matches.length}</strong> matching results for <strong>"${data.query}"</strong>`;
			}
			list.prepend(info);
		},
		noResults: true,
		maxResults: 15,
		tabSelect: true
	},
	resultItem: {
		element: (item, data) => {
			// Modify Results Item Style
			item.style = "display: flex; justify-content: space-between;";
			// Modify Results Item Content
			item.innerHTML = `
      <span style="text-overflow: ellipsis; white-space: nowrap; overflow: hidden;">
        ${data.match}
      </span>
      <span style="display: flex; align-items: center; font-size: 13px; font-weight: 100; text-transform: uppercase; color: rgba(0,0,0,.2);">
        ${data.key}
      </span>`;
		},
		highlight: true
	},
	events: {
		input: {
            selection: (event) => {
                const selection = event.detail.selection.value;
                autoCompleteJS.input.value = selection.location;
                inputLat.value = selection.lat;
                inputLng.value = selection.lng;
            }
        }
	}
});
