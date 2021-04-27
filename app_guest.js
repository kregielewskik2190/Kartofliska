const init = () => {


	document.querySelector("#toggleMeetings").addEventListener("click", () => {
		document.querySelector("#meetings").classList.toggle("container__box--hidden");
	});
	document.querySelector("#toggleTeams").addEventListener("click", () => {
		document.querySelector("#teams").classList.toggle("container__box--hidden");
	});
	document.querySelector("#toggleStadiums").addEventListener("click", () => {
		document.querySelector("#stadiums").classList.toggle("container__box--hidden");
	});

	const team = document.querySelector("#spotkania_druzyna");

	team.addEventListener("change", (e) => {
		const newValue = e.target.value;

		const rows = document.querySelector("#spotkania_table").querySelectorAll(".container__row");
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

	const league = document.querySelector("#liga");

	league.addEventListener("change", (e) => {
		const newValue = (e.target.value);

		const rows = document.querySelector("#spotkania_table").querySelectorAll(".container__row");
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

	const dateButton = document.querySelector("#date");

	dateButton.addEventListener("click", () => {
		const dateFrom = document.querySelector("#date-from");
		const dateTo = document.querySelector("#date-to");

		const rows = document.querySelector("#spotkania_table").querySelectorAll(".container__row");

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