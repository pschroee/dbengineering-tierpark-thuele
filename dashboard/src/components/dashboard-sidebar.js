import AttractionsIcon from "@mui/icons-material/Attractions";
import BarChartIcon from "@mui/icons-material/BarChart";
import CalendarMonthIcon from "@mui/icons-material/CalendarMonth";
import GroupIcon from "@mui/icons-material/Group";
import SettingsIcon from "@mui/icons-material/Settings";
import { useMediaQuery } from "@mui/material";
import Box from "@mui/material/Box";
import Divider from "@mui/material/Divider";
import Drawer from "@mui/material/Drawer";
import Typography from "@mui/material/Typography";
import NextLink from "next/link";
import { useRouter } from "next/router";
import { useEffect } from "react";
import NavItem from "./../components/nav-item";

const items = [
  {
    href: "/",
    icon: <BarChartIcon />,
    title: "Dashboard",
  },
  {
    href: "/mitarbeiter",
    icon: <GroupIcon />,
    title: (
      <>
        Verwaltung
        <br />
        Mitarbeiter
      </>
    ),
  },
  {
    href: "/fahrgeschaefte",
    icon: <AttractionsIcon />,
    title: "Verwaltung Fahrgeschäfte",
  },
  {
    href: "/arbeitsplanung-fahrgeschaefte",
    icon: <CalendarMonthIcon />,
    title: "Arbeitsplanung Fahrgeschäfte",
  },
  {
    href: "/rollenberechtigungen",
    icon: <SettingsIcon />,
    title: "Verwaltung Rollenberechtigungen",
  },
];

export const DashboardSidebar = (props) => {
  const { open, onClose } = props;
  const router = useRouter();
  const lgUp = useMediaQuery((theme) => theme.breakpoints.up("lg"), {
    defaultMatches: true,
    noSsr: false,
  });

  useEffect(
    () => {
      if (!router.isReady) {
        return;
      }

      if (open) {
        onClose?.();
      }
    },
    // eslint-disable-next-line react-hooks/exhaustive-deps
    [router.asPath]
  );

  const content = (
    <>
      <Box
        sx={{
          display: "flex",
          flexDirection: "column",
          height: "100%",
        }}
      >
        <div>
          <NextLink href="/" passHref>
            <Box sx={{ px: 3, pt: 3 }}>
              <Box
                sx={{
                  alignItems: "center",
                  backgroundColor: "rgba(255, 255, 255, 0.04)",
                  cursor: "pointer",
                  display: "flex",
                  justifyContent: "space-between",
                  px: 3,
                  py: "11px",
                  borderRadius: 1,
                }}
              >
                <Typography color="inherit" variant="h5">
                  Tierpark Thüle
                </Typography>
              </Box>
            </Box>
          </NextLink>
        </div>
        <Divider
          sx={{
            borderColor: "#2D3748",
            my: 3,
          }}
        />
        <Box sx={{ flexGrow: 1, px: 0 }}>
          {items.map((item) => (
            <NavItem key={item.title} icon={item.icon} href={item.href} title={item.title} />
          ))}
        </Box>
      </Box>
    </>
  );

  if (lgUp) {
    return (
      <Drawer
        anchor="left"
        open
        PaperProps={{
          sx: {
            backgroundColor: "neutral.900",
            color: "#FFFFFF",
            width: 280,
          },
        }}
        variant="permanent"
      >
        {content}
      </Drawer>
    );
  }

  return (
    <Drawer
      anchor="left"
      onClose={onClose}
      open={open}
      PaperProps={{
        sx: {
          backgroundColor: "neutral.900",
          color: "#FFFFFF",
          width: 280,
        },
      }}
      sx={{ zIndex: (theme) => theme.zIndex.appBar + 100 }}
      variant="temporary"
    >
      {content}
    </Drawer>
  );
};

export default DashboardSidebar;
