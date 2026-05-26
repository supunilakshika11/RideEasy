import { useState } from "react";

const faqs = [
  ["What documents are required?", "A valid driving license, national ID or passport, and a payment method are required."],
  ["Can I extend my booking?", "Yes. Open your customer dashboard or contact support before the return time."],
  ["Do rentals include insurance?", "Basic protection is included, with optional premium protection during booking."],
];

export default function Contact() {
  const [openFaq, setOpenFaq] = useState(0);
  const [sent, setSent] = useState(false);

  return (
    <section className="contact-layout">
      <div className="page-title">
        <p className="eyebrow dark">Support</p>
        <h1>Contact Our Team</h1>
        <p>Get help with bookings, vehicle availability, payments, and returns.</p>
      </div>

      <div className="contact-grid">
        <form className="form-stack wide" onSubmit={(event) => event.preventDefault()}>
          <h2>Send us a message</h2>
          <label>
            Full name
            <input placeholder="Your name" />
          </label>
          <label>
            Email
            <input type="email" placeholder="you@example.com" />
          </label>
          <label>
            Subject
            <input placeholder="Booking question" />
          </label>
          <label>
            Message
            <textarea placeholder="How can we help?" />
          </label>
          <button className="button-primary full" onClick={() => setSent(true)} type="button">
            Send Message
          </button>
          {sent && <p className="inline-success">Message sent. Our team will respond shortly.</p>}
        </form>

        <aside className="contact-aside">
          <section className="panel contact-info">
            <h2>Contact Information</h2>
            <p><strong>Hotline</strong> +94 77 123 4567</p>
            <p><strong>Email</strong> support@rideeasy.lk</p>
            <p><strong>Office</strong> 24 Marine Drive, Colombo</p>
            <p><strong>Hours</strong> Daily, 7:00 AM - 10:00 PM</p>
          </section>

          <section className="panel">
            <h2>Frequently Asked Questions</h2>
            <div className="faq-list">
              {faqs.map(([question, answer], index) => (
                <button
                  className={openFaq === index ? "faq-item active" : "faq-item"}
                  key={question}
                  onClick={() => setOpenFaq(openFaq === index ? -1 : index)}
                  type="button"
                >
                  <span>{question}</span>
                  {openFaq === index && <small>{answer}</small>}
                </button>
              ))}
            </div>
          </section>
        </aside>
      </div>
    </section>
  );
}
