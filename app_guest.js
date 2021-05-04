	const spotkaniaInit = () => {

	const meetings = document.querySelector("#meetings");
	const team = document.querySelector("#spotkania_druzyna");

	team.addEventListener("change", (e) => {
		const newValue = e.target.value;

		const rows = meetings.querySelector("#spotkania_table").querySelectorAll(".container__row");
		[...rows].map(r => r.classList.remove("container__row--hidden"));
		rows.forEach((row, i) => {
				if (i == 0) return;

				const a = row.querySelector("[data-team='a']");
				const b = row.querySelector("[data-team='b']");

				if (!a.textContent.includes(newValue) && !b.textContent.includes(newValue))
					row.classList.add("container__row--hidden");
		});

		if (newValue === "Brak")
		[...rows].map(r => r.classList.remove("container__row--hidden"));

	});

	const league = meetings.querySelector("#liga");

	league.addEventListener("change", (e) => {
		const newValue = (e.target.value);

		const rows = meetings.querySelector("#spotkania_table").querySelectorAll(".container__row");
			rows.forEach((row, i) => {
				if (i == 0) return;

				const liga = row.querySelector("[data-team='liga']");
					row.classList.remove("container__row--hidden");
				if (parseInt(liga.textContent) !== parseInt(newValue))
					row.classList.add("container__row--hidden");
			});

		if (newValue === "Brak")

			[...rows].map(r => r.classList.remove("container__row--hidden"));
	});

	const dateButton = meetings.querySelector("#date");

	dateButton.addEventListener("click", () => {
		const dateFrom = meetings.querySelector("#date-from");
		const dateTo = meetings.querySelector("#date-to");

		const rows = meetings.querySelector("#spotkania_table").querySelectorAll(".container__row");

 		[...rows].map(r => r.classList.remove("container__row--hidden"));


		if (dateFrom.valueAsNumber > dateTo.valueAsNumber) return alert("Błędnie wskazany zakres danych");

			rows.forEach((row, i) => {
				if (i == 0) return;

				const time = row.querySelector("[data-team='time']");
				const date = new Date(time.textContent);

				if (dateFrom.valueAsNumber > date.getTime())
					row.classList.add("container__row--hidden");
				if (dateTo.valueAsNumber < date.getTime())
					row.classList.add("container__row--hidden");
			});
	});
}

const sezon = (date) => {
	if (date.getMonth() > 6) return date.getFullYear();
	return date.getFullYear() - 1;
};

const addRows = (elements) => {
	const results = document.querySelector("#results");
	const table = results.querySelector("#wyniki_table");

	table.querySelectorAll(".container__row").forEach((r, i) => (i !== 0) ? r.parentNode.removeChild(r) : '');

	elements.forEach(e => {
		const row = document.createElement("div");
		row.classList.add("container__row");
		Object.keys(e).forEach(v => {
			const cell = document.createElement("div");
			cell.classList.add("container__cell");
			if (v === "time") {
				const d = new Date(e[v]);

				cell.innerText = `${(d.getDate() + 1)}-${(d.getMonth() + 1)}-${(d.getFullYear())}`;
			} else if (!e[v]) {}
			else
			cell.innerText = e[v];
			row.appendChild(cell);
		});
		table.appendChild(row);
	});
}

const wynikiInit = () => {

	const results = document.querySelector("#results");
	const wyniki_sezon = results.querySelector("#wyniki_sezon");
	const selectTeams = results.querySelector("#wyniki_druzyna");
	const wyniki_kolejka = results.querySelector("#wyniki_kolejka");


	const resultsDB = [];

	results.querySelector("#wyniki_table").querySelectorAll(".container__row").forEach((row, i) => {
		if (i == 0) return;

		const result = {};
		const date = new Date(row.querySelector("[data-team='time']").textContent);
		result.league = parseInt(row.querySelector("[data-team='liga']").textContent);
		result.teamA = (row.querySelector("[data-team='a']").textContent);
		result.teamB = (row.querySelector("[data-team='b']").textContent);
		result.resultA = parseInt(row.querySelector("[data-team='wynik-a']").textContent);
		result.resultB = parseInt(row.querySelector("[data-team='wynik-b']").textContent);
		result.queue = parseInt(row.querySelector("[data-team='kolejka']").textContent);
		result.sezon = sezon(date);
		result.time = parseInt(date.getTime());
		resultsDB.push(result);
		row.parentNode.removeChild(row);
	});

	const league = results.querySelector("#liga");

	league.addEventListener("change", (e) => {
		const newValue = parseInt(e.target.value);

		selectTeams.querySelectorAll("option").forEach((option, i) => {
			option.disabled = false;
			if (option.dataset.liga && parseInt(option.dataset.liga) !== newValue) option.disabled = true;
		});
	});

	document.querySelector("#filterResults").addEventListener("click", () => {
		if (league.value === "Brak" || !wyniki_sezon.valueAsNumber) return;
		let resultsRecords = resultsDB.filter(r => r.league === parseInt(league.value));

		if (resultsRecords.length === 0) return;
		if (wyniki_sezon) resultsRecords = resultsRecords.filter(r => r.sezon === wyniki_sezon.valueAsNumber);
		if (selectTeams.value !== "Brak") resultsRecords = resultsRecords.filter(r => r.teamA === selectTeams.value || r.teamB === selectTeams.value);
		else if (wyniki_kolejka.valueAsNumber) resultsRecords = resultsRecords.filter(r => r.queue === wyniki_kolejka.valueAsNumber);

		addRows(resultsRecords);
	})

	// team.addEventListener("change", (e) => {
	// 	const newValue = e.target.value;

	// 	const rows = results.querySelector("#wyniki_table").querySelectorAll(".container__row");
	// 	[...rows].map(r => r.classList.remove("container__row--hidden"));
	// 	rows.forEach((row, i) => {
	// 			if (i == 0) return;

	// 			const a = row.querySelector("[data-team='a']");
	// 			const b = row.querySelector("[data-team='b']");

	// 			if (!a.textContent.includes(newValue) && !b.textContent.includes(newValue))
	// 				row.classList.add("container__row--hidden");
	// 	});

	// 	if (newValue === "Brak")
	// 	[...rows].map(r => r.classList.remove("container__row--hidden"));

	// });


	// const dateButton = results.querySelector("#date");

	// dateButton.addEventListener("click", () => {
	// 	const dateFrom = results.querySelector("#date-from");
	// 	const dateTo = results.querySelector("#date-to");

	// 	const rows = results.querySelector("#wyniki_table").querySelectorAll(".container__row");

 	// 	[...rows].map(r => r.classList.remove("container__row--hidden"));


	// 	if (dateFrom.valueAsNumber > dateTo.valueAsNumber) return alert("Błędnie wskazany zakres danych");

	// 		rows.forEach((row, i) => {
	// 			if (i == 0) return;

	// 			const time = row.querySelector("[data-team='time']");
	// 			const date = new Date(time.textContent);

	// 			if (dateFrom.valueAsNumber > date.getTime())
	// 				row.classList.add("container__row--hidden");
	// 			if (dateTo.valueAsNumber < date.getTime())
	// 				row.classList.add("container__row--hidden");
	// 		});
	// });

}

const stadionyInit = () => {
	const stadionMiasto = document.querySelector("#stadiony_miasto");

	stadionMiasto.addEventListener("change", (e) => {
		if (e.target.value === "0,0") return r.classList.remove("container__row--hidden");

		const lat = parseFloat(e.target.value.split(',')[0]);
		const lon = parseFloat(e.target.value.split(',')[1]);
		const distance = parseInt(document.querySelector("#stadion_odleglosc").value);

		const rows = document.querySelector("#stadiony_table").querySelectorAll(".container__row");


		[...rows].map(r => r.classList.remove("container__row--hidden"));

		const distances = [...rows].map(r => {
			const cell = r.querySelector("[data-val='pos']");
			if (cell === null) return 0;
			const latLocal = parseFloat(cell.dataset.lat);
			const lonLocal = parseFloat(cell.dataset.lon);

			return findDistance(lat, lon, latLocal, lonLocal);
		});

		rows.forEach((row, i) => {
			if (i == 0) return;

			if (distances[i] > distance)
				row.classList.add("container__row--hidden");
		})
	});

	const stadionOdleglosc = document.querySelector("#stadion_odleglosc");

	stadionOdleglosc.addEventListener("change", (e) => {
		if (stadionMiasto.value === "0,0") return r.classList.remove("container__row--hidden");

		const lat = parseFloat(stadionMiasto.value.split(',')[0]);
		const lon = parseFloat(stadionMiasto.value.split(',')[1]);
		const distance = parseInt(e.target.value);

		const rows = document.querySelector("#stadiony_table").querySelectorAll(".container__row");


		[...rows].map(r => r.classList.remove("container__row--hidden"));

		const distances = [...rows].map(r => {
			const cell = r.querySelector("[data-val='pos']");
			if (cell === null) return 0;
			const latLocal = parseFloat(cell.dataset.lat);
			const lonLocal = parseFloat(cell.dataset.lon);

			return findDistance(lat, lon, latLocal, lonLocal);
		});

		rows.forEach((row, i) => {
			if (i == 0) return;

			if (distances[i] > distance)
				row.classList.add("container__row--hidden");
		})
	});
}


const init = () => {

	document.querySelector("#toggleMeetings").addEventListener("click", () => {
		document.querySelectorAll(".container__box:not([id=meetings])").forEach(e => e.classList.add("container__box--hidden"));
		document.querySelector("#meetings").classList.toggle("container__box--hidden");
	});

	document.querySelector("#toggleResults").addEventListener("click", () => {
		document.querySelectorAll(".container__box:not([id=results])").forEach(e => e.classList.add("container__box--hidden"));
		document.querySelector("#results").classList.toggle("container__box--hidden");
	});

	document.querySelector("#toggleTeams").addEventListener("click", () => {
		document.querySelectorAll(".container__box:not([id=teams])").forEach(e => e.classList.add("container__box--hidden"));
		document.querySelector("#teams").classList.toggle("container__box--hidden");
	});
	document.querySelector("#toggleStadiums").addEventListener("click", () => {
		document.querySelectorAll(".container__box:not([id=stadiums])").forEach(e => e.classList.add("container__box--hidden"));
		document.querySelector("#stadiums").classList.toggle("container__box--hidden");
	});

	spotkaniaInit();

	wynikiInit();

	stadionyInit();
};

window.addEventListener("load", init);


const deg2rad = (degrees) => degrees * Math.PI / 180;
const rad2deg = (radians) => radians * 180 / Math.PI;


const findDistance = (lat1, lon1, lat2, lon2) => {
	const R = 6371;
	const lat1Rad = deg2rad(lat1);
	const lat2Rad = deg2rad(lat2);
	const letDeltaRad = deg2rad(lat2 - lat1);
	const lonDeltaRad = deg2rad(lon2 - lon1);

	const a = Math.sin(letDeltaRad/2) * Math.sin(letDeltaRad/2) +
		Math.cos(lat1Rad) * Math.cos(lat2Rad) *
		Math.sin(lonDeltaRad/2) * Math.sin(lonDeltaRad/2);
	const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));

	return R * c;

}