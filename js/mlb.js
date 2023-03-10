// const options = {
//   method: "GET",
//   headers: {
//     "X-RapidAPI-Key": "485bb2fb02msh1f89e9fb46af9f6p12dba8jsnb821260832dc",
//     "X-RapidAPI-Host": "mlb-data.p.rapidapi.com",
//   },
// };

// //* RENDER FAQ
// function renderFaqwrap(array) {
//   return array
//     .map((item) => {
//       return `
//       <div class="teams_item border p-4 m-1">
//       <h5>${item.mlb_org}</h5>
//       <p class="mb-0">${item.league_full}</p>
//       <p class="mb-0">${item.division}</p>
//       </div>
//       `;
//     })
//     .join(" ");
// }

// //* Ir a Render: FAQ
// function renderFaq() {
//   return $(".teams").html(renderFaqwrap(dataFaq));
// }

// (async function () {
//   const resp = await fetch("https://mlb-data.p.rapidapi.com/json/named.team_all_season.bam?sport_code='mlb'&season='2023'&all_star_sw='N'&sort_order=name_asc", options);
//   dataFaq = await resp.json();
//   dataFaq = dataFaq.team_all_season.queryResults.row;
//   console.log(dataFaq);
//   renderFaq();
// })();


// Reemplaza 'TU-LLAVE-DE-AUTENTICACION' con la clave de autenticación que obtuviste al registrarte en la API.
var rapidapiKey = '485bb2fb02msh1f89e9fb46af9f6p12dba8jsnb821260832dc';

// Hacemos una petición AJAX a la API.
$.ajax({
  url: "https://mlb-data.p.rapidapi.com/json/named.team_all_season.bam?sport_code='mlb'&season='2023'&all_star_sw='N'&sort_order=name_asc",
  headers: {
    'X-RapidAPI-Key': rapidapiKey,
    // "X-RapidAPI-Host": "mlb-data.p.rapidapi.com",
  },
  method: 'GET',
  success: function(response) {
    // Accedemos a los equipos de la Liga Nacional dentro de la respuesta.
    var teams = response.team_all_season.queryResults.row.filter(function(row) {
      return row.league == 'NL';
    });

    // Imprimimos los equipos en la consola.
    teams.forEach(function(team) {
      console.log(team.name_display_full);
    });
  },
  error: function(error) {
    console.log(error);
  }
});
