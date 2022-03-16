const HOST = "/api/public";

const fetchapi = async (path, method, body) => {
  let payload = {};
  switch (method) {
    case "GET":
      payload = {};
      break;
    case "DELETE":
      payload = {};
      break;
    case "POST":
      payload = {
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(body),
      };
      break;
    case "PUT":
      payload = {
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(body),
      };
      break;
    default:
      break;
  }
  const response = await fetch(`${HOST}/${path}`, {
    method: method,
    ...payload,
  });
  if (!response.ok) {
    const message = `An error has occured: ${response.status}`;
    throw new Error(message);
  }
  const json = await response.json();
  return json;
};

export default fetchapi;
