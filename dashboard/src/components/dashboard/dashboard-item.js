import Avatar from "@mui/material/Avatar";
import Card from "@mui/material/Card";
import CardContent from "@mui/material/CardContent";
import Grid from "@mui/material/Grid";
import Typography from "@mui/material/Typography";

const DashboardItem = ({ title, value, variant = "primary", icon, ...rest }) => (
  <Card sx={{ height: "100%" }} {...rest}>
    <CardContent>
      <Grid container spacing={3} sx={{ justifyContent: "space-between" }}>
        <Grid item>
          <Typography color="textSecondary" gutterBottom variant="overline">
            {`${title}`.toUpperCase()}
          </Typography>
          <Typography color="textPrimary" variant="h4">
            {value}
          </Typography>
        </Grid>
        <Grid item>
          <Avatar
            sx={{
              backgroundColor: `${variant}.main`,
              height: 56,
              width: 56,
            }}
          >
            {icon}
          </Avatar>
        </Grid>
      </Grid>
    </CardContent>
  </Card>
);

export default DashboardItem;
