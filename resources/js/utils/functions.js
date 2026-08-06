function getCurrentLocation() {
    return new Promise((resolve) => {
        if (!navigator.geolocation) {
            resolve({
                error_message:
                    "Geolocalização não suportada no seu navegador.",
                latitude: "",
                longitude: "",
            });
            return;
        }
        navigator.geolocation.getCurrentPosition(
            (position) => {
                resolve({
                    error_message: "",
                    latitude: Number(position.coords.latitude.toFixed(8)),
                    longitude: Number(position.coords.longitude.toFixed(8)),
                });
            },
            (error) => {
                let error_message = "";
                switch (error.code) {
                    case error.PERMISSION_DENIED:
                        error_message =
                            "Permissão de localização negada. Habilite para usar esse recurso.";
                        break;
                    case error.POSITION_UNAVAILABLE:
                        error_message =
                            "Localização indisponível no momento.";
                        break;
                    case error.TIMEOUT:
                        error_message =
                            "Tempo esgotado ao buscar localização.";
                        break;
                    default:
                        error_message = "Erro ao obter localização.";
                }
                resolve({
                    error_message,
                    latitude: "",
                    longitude: "",
                });
            },
            { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 },
        );
    });
}
export {
    getCurrentLocation,
}
