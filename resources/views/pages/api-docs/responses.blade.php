<div class="tab-pane fade " id="responses" role="tabpanel">
    <h3>The Responses of API for (SEND MESSAGE) | JSON</h3>
    <p>Response Success</p>
    <pre class="bg-dark text-white">
        <code class="json">
{
    "status": true,
    "msg": "Message sent successfully!"
}
        </code>
      </pre>
    <p>Response Failed</p>
    <pre class="bg-dark text-white">
        <code class="json">
{
    "status": false,
    "msg": "Message failed to send!"
}
        </code>
      </pre>
    <p>Response INVALID Data</p>
    <pre class="bg-dark text-white">
          <code class="json">
{
    "status": false,
    "msg": "Invalid data!",
    "errors": { } // list of errors
}
          </code>
        </pre>
    <p>Response failed access whatsapp server</p>
    <pre class="bg-dark text-white">
            <code class="json">
{
    "status": false,
    "msg": "Failed to send message!",
    "errors": "Failed to access whatsapp server"
}
</code>
</pre>

</div>
