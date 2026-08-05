export default function MetabolicSection ({ contentData = data }){
  if (!contentData) return null;

  const {
    section_heading,
    image_url,
    image_alt,
    sub_heading,
    paragraphs,
    benefits_heading,
    benefits_list,
    donation_details,
  } = contentData;

  return (
    <section className="akd-md-wrapper">
      <div className="akd-md-container">
        {/* Main Section Heading */}
        {section_heading && (
          <h2 className="akd-md-main-title">{section_heading}</h2>
        )}

        {/* Top Grid: Image + Description */}
        <div className="akd-md-grid">
          {/* Left Column: Banner Image */}
          <div className="akd-md-image-col">
            <div className="akd-md-image-card">
              <img
                src={`/assets/images${image_url}`}
                alt={image_alt || 'Donation Cause'}
                className="akd-md-img"
              />
            </div>
          </div>

          {/* Right Column: Narrative Content */}
          <div className="akd-md-content-col">
            {sub_heading && (
              <h3 className="akd-md-sub-heading">{sub_heading}</h3>
            )}

            {paragraphs?.map((text, idx) => (
              <p key={idx} className="akd-md-text">
                {text}
              </p>
            ))}

            {benefits_heading && (
              <p className="akd-md-text akd-md-benefits-title">
                {benefits_heading}
              </p>
            )}

            {benefits_list?.length > 0 && (
              <ol className="akd-md-list">
                {benefits_list.map((item, idx) => (
                  <li key={idx}>
                    <strong>{item}</strong>
                  </li>
                ))}
              </ol>
            )}
          </div>
        </div>

        {/* Bottom Section: Donation Bank Details */}
        {donation_details && (
          <div className="akd-md-bank-card">
            <h3 className="akd-md-donate-title">{donation_details.title}</h3>

            <div className="akd-md-bank-info">
              {donation_details.bank_name && (
                <p className="akd-md-bank-name">
                  <strong>{donation_details.bank_name}</strong>
                </p>
              )}

              <p>
                <strong>Account Title:</strong> {donation_details.account_title}
              </p>
              <p>
                <strong>Account No.:</strong> {donation_details.account_no}
              </p>
              <p>
                <strong>IBAN:</strong> {donation_details.iban}
              </p>
              <p>
                <strong>Swift Code:</strong> {donation_details.swift_code}
              </p>
              <p>
                <strong>Branch Name:</strong> {donation_details.branch_name}
              </p>
            </div>

            <p className="akd-md-whatsapp-note">
              {donation_details.whatsapp_note}
            </p>

            <p className="akd-md-contact-line">
              <a
                href={`https://${donation_details.website}`}
                target="_blank"
                rel="noopener noreferrer"
              >
                {donation_details.website}
              </a>{' '}
              | <strong>Phone:</strong> {donation_details.phone} |{' '}
              <strong>Whatsapp:</strong> {donation_details.whatsapp}
            </p>

            {donation_details.footer_note && (
              <p className="akd-md-footer-note">
                {donation_details.footer_note}
              </p>
            )}
          </div>
        )}
      </div>
    </section>
  );
};
