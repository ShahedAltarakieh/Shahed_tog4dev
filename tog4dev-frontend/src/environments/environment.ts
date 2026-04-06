export const environment = {
    production: false,
    gtmId: "GTM-59NPBJP322",
    apiUrl: 'https://admin.tog4dev.com/',
    networkUrl: 'https://ap-gateway.mastercard.com/checkout/pay/',
    /** Card API gateway: 'Network' | 'AuthorizeNet' — must align with backend CARD_PAYMENT_GATEWAY */
    cardPaymentGateway: 'Network' as 'Network' | 'AuthorizeNet',
};
