const types = {
    customer: 'customer',
    owner: 'owner',
    developer: 'developer',
}
const typesBr = {
    customer: 'Cliente',
    owner: 'Propietário',
    developer: 'Desenvolvedor',
}

function getUserTypePTBR(type) {
    if (!type) return "N/A";
    return typesBr[type];
}

const userType = {
    types,
    typesBr,
    getUserTypePTBR
}

export default userType;